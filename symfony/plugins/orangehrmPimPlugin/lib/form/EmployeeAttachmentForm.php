<?php
/**
 * OrangeHRM Enterprise is a closed sourced comprehensive Human Resource Management (HRM)
 * System that captures all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM Inc is the owner of the patent, copyright, trade secrets, trademarks and any
 * other intellectual property rights which subsist in the Licensed Materials. OrangeHRM Inc
 * is the owner of the media / downloaded OrangeHRM Enterprise software files on which the
 * Licensed Materials are received. Title to the Licensed Materials and media shall remain
 * vested in OrangeHRM Inc. For the avoidance of doubt title and all intellectual property
 * rights to any design, new software, new protocol, new interface, enhancement, update,
 * derivative works, revised screen text or any other items that OrangeHRM Inc creates for
 * Customer shall remain vested in OrangeHRM Inc. Any rights not expressly granted herein are
 * reserved to OrangeHRM Inc.
 *
 * You should have received a copy of the OrangeHRM Enterprise  proprietary license file along
 * with this program; if not, write to the OrangeHRM Inc. 538 Teal Plaza, Secaucus , NJ 0709
 * to get the file.
 *
 */


/**
 * Form class for employee attachments
 */
class EmployeeAttachmentForm extends BaseForm {

    public function configure() {

        // Note: Widget names were kept from old non-symfony version
        $this->setWidgets(array(
            'EmpID' => new sfWidgetFormInputHidden(),
            'seqNO' => new sfWidgetFormInputHidden(),
            'MAX_FILE_SIZE' => new sfWidgetFormInputHidden(),
            'ufile' => new sfWidgetFormInputFile(),
            'txtAttDesc' => new sfWidgetFormInputText(),
            'screen' => new sfWidgetFormInputHidden(),
            'commentOnly' => new sfWidgetFormInputHidden(),
        ));

        $this->setValidators(array(
            'EmpID' => new sfValidatorNumber(array('required' => true, 'min'=> 0)),
            'seqNO' => new sfValidatorNumber(array('required' => false, 'min'=> 0)),
            'MAX_FILE_SIZE' => new sfValidatorNumber(array('required' => true)),
            'ufile' => new sfValidatorFile(array('required' => false, 
                'max_size'=>1000000), array('max_size' => __('Attachment Size Exceeded'))),
            'txtAttDesc' => new sfValidatorString(array('required' => false)),            
            'screen' => new sfValidatorString(array('required' => true)),
            'commentOnly' => new sfValidatorString(array('required' => false)),
        ));

        // set up your post validator method
        $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array(
            'callback' => array($this, 'postValidate')
          ))
        );
    }

    public function postValidate($validator, $values) {

        // If seqNo given, ufile should not be given.
        // If seqNo not given and commentsonly was clicked, ufile should be given
        $attachId = $values['seqNO'];
        $file = $values['ufile'];
        $commentOnly = $this->getValue('commentOnly') == "1";        

        if (empty($attachId) && empty($file)) {
            $message = sfContext::getInstance()->getI18N()->__('Upload file missing');
            $error = new sfValidatorError($validator, $message);
            throw new sfValidatorErrorSchema($validator, array('' => $error));
        } else if (!empty($attachId) && $commentOnly && !empty($file)) {
            $message = sfContext::getInstance()->getI18N()->__('Invalid input');
            $error = new sfValidatorError($validator, $message);
            throw new sfValidatorErrorSchema($validator, array('' => $error));
        }
        
        return $values;
    }

    /**
     * Save employee contract
     */
    public function save() {

        $empNumber = $this->getValue('EmpID');
        $attachId = $this->getValue('seqNO');

        $empAttachment = false;

        if (empty($attachId)) {
            $q = Doctrine_Query::create()
                    ->select('MAX(a.attach_id)')
                    ->from('EmployeeAttachment a')
                    ->where('a.emp_number = ?', $empNumber);
            $result = $q->execute(array(), Doctrine::HYDRATE_ARRAY);

            if (count($result) != 1) {
                throw new PIMServiceException('MAX(a.attach_id) failed.');
            }
            $attachId = is_null($result[0]['MAX']) ? 1 : $result[0]['MAX'] + 1;

        } else {
            $q = Doctrine_Query::create()
                    ->select('a.emp_number, a.attach_id')
                    ->from('EmployeeAttachment a')
                    ->where('a.emp_number = ?', $empNumber)
                    ->andWhere('a.attach_id = ?', $attachId);
            $result = $q->execute();

            if ($result->count() == 1) {
                $empAttachment = $result[0];
            } else {
                throw new PIMServiceException('Invalid attachment');
            }
        }
        
        //
        // New file upload
        //
        $newFile = false;
        
        if ($empAttachment === false) {

            $empAttachment = new EmployeeAttachment();
            $empAttachment->emp_number = $empNumber;
            $empAttachment->attach_id = $attachId;
            $newFile = true;
        }
        
        $commentOnly = $this->getValue('commentOnly');        
        if ($newFile || ($commentOnly == '0')) {
            $file = $this->getValue('ufile');
            $tempName = $file->getTempName();

            $empAttachment->size = $file->getSize();
            $empAttachment->filename = $file->getOriginalName();
            $empAttachment->attachment = file_get_contents($tempName);;
            $empAttachment->file_type = $file->getType();
            $empAttachment->screen = $this->getValue('screen');
            
            $empAttachment->attached_by = $this->getOption('loggedInUser');
            $empAttachment->attached_by_name = $this->getOption('loggedInUserName');
            // emp_id and name
        }

        $empAttachment->description = $this->getValue('txtAttDesc');

        $empAttachment->save();
    }

}
