<?php
/**
 * Service Class for Performance Review
 *
 * @author Sujith T
 */
class PerformanceReviewService extends BaseService {

   private $performanceReviewDao = null;
   
   const EMAIL_TEMPLATE_REVIWER_SUBMIT     =   'performance_submit.txt';
   const EMAIL_TEMPLATE_HRADMIN_APPROVE    =   'performance_approve.txt';
   const EMAIL_TEMPLATE_HRADMIN_REJECT     =   'performance_reject.txt';
   const EMAIL_TEMPLATE_ADD_REVIEW         =   'add-review.txt';

  /**
   * Setting the PerformanceReviewDao
   * @param PerformanceReviewDao dao
   */
   public function setPerformanceReviewDao(PerformanceReviewDao $dao) {
      $this->performanceReviewDao = $dao;
   }

   /**
    * Return PerformanceReviewDao Instance
    * @returns PerformanceReviewDao
    */
   public function getPerformanceReviewDao() {
      return $this->performanceReviewDao;
   }

   /**
    * Save PerformanceReview
    * @param PerformanceReview $performanceReview
    * @returns PerformanceReview
    * @throws PerformanceServiceException
    */
   public function savePerformanceReview(PerformanceReview $performanceReview) {
      try{
         return $this->performanceReviewDao->savePerformanceReview($performanceReview);
      } catch(Exception $e) {
         throw new PerformanceServiceException($e->getMessage());
      }
   }

    /**
     * Read Performance Review
     * @param int $reviewId
     * @return PerformanceReview
     * @throws PerformanceServiceException
     */
    public function readPerformanceReview($reviewId) {
        try {
            return $this->performanceReviewDao->readPerformanceReview($reviewId);
        } catch(Exception $e) {
            throw new PerformanceServiceException($e->getMessage());
        }
    }

    /**
     * Delete PerformanceReview
     * @param array $reviewList
     * @returns boolean
     * @throws PerformanceServiceException
     */
    public function deletePerformanceReview($reviewList) {
      try {
         return $this->performanceReviewDao->deletePerformanceReview($reviewList);
      } catch(Exception $e) {
         throw new PerformanceServiceException($e->getMessage());
      }
    }

    /**
     * Get All PerformanceReviews
     */
    public function getPerformanceReviewList() {
      try {
         return $this->performanceReviewDao->getPerformanceReviewList();
      } catch(Exception $e) {
         throw new PerformanceServiceException($e->getMessage());
      }
    }

    /**
     * Search for PerformanceReviews on multiple criteria
     * @param array $searchParam
     * @param $offset
     * @param $limit
     * @returns Collection
     * @throws PerformanceServiceException
     */
    public function searchPerformanceReview($clues, $offset=null, $limit=null) {

      try {
         return $this->performanceReviewDao->searchPerformanceReview($clues, $offset, $limit);
      } catch(Exception $e) {
         throw new PerformanceServiceException($e->getMessage());
      }

    }

    /**
     * Counting the reviews
     * @param array $searchParam
     * @returns int
     * @throws PerformanceServiceException
     */
    public function countReviews($clues) {
        
        try {
            return $this->performanceReviewDao->countReviews($clues);
        } catch(Exception $e) {
            throw new PerformanceServiceException($e->getMessage());
        }

    }

    /**
     * Save Performance Review
     * @param PerformanceReview $performanceReview
     * @return PerformanceReview
     */
    public function changePerformanceStatus(PerformanceReview $performanceReview, $status)
    {
        try {

                switch($status)
                {
                    case PerformanceReview::PERFORMANCE_REVIEW_STATUS_SUBMITTED:
                        $this->sendReviwerSubmitEmail($performanceReview);
                        break;

                    case PerformanceReview::PERFORMANCE_REVIEW_STATUS_REJECTED:
                        $this->sendReviwRejectEmail($performanceReview);
                        break;

                    case PerformanceReview::PERFORMANCE_REVIEW_STATUS_APPROVED:
                        $this->sendReviwApproveEmail($performanceReview);
                        break;
                }

                return $this->performanceReviewDao->updatePerformanceReviewStatus($performanceReview, $status );
                
                /*
                $performanceReview->setState($status);
                $savedInstance = $this->performanceReviewDao->savePerformanceReview($performanceReview);
                if($savedInstance instanceof PerformanceReview) {
                   return true;
                }
                return false;
                */
                
        } catch (Exception $e) {
            throw new PerformanceServiceException($e->getMessage());
        }
    }

    /**
     * Add New comments to performance review
     * @return unknown_type
     */
    public function addComment( PerformanceReview $performanceReview ,$comment ,$user){
        
        try {
        
            $performanceReviewComment = new PerformanceReviewComment();

            $performanceReviewComment->setPrId($performanceReview->getId());
            $performanceReviewComment->setComment($comment);
            if(is_numeric($user)) {
            	$performanceReviewComment->setEmployeeId($user);
            }
            
            $performanceReviewComment->setCreateDate(date('Y-m-d'));
            $performanceReviewComment->save();

        } catch ( Exception $e) {
            throw new AdminServiceException($e->getMessage());
        }
    }

    /**
     * Send Reviwer Submit email
     * PerformanceReview $performanceReview
     * @return boolean
     */
    public function sendReviwerSubmitEmail(PerformanceReview $performanceReview ){

        try{

        $mailNotificationService = new EmailNotificationService();
        $subscriptions = $mailNotificationService->getSubscribersByNotificationId(EmailNotification::PERFORMANCE_SUBMISSION);

	foreach ($subscriptions as $subscription) {
	
            if ($subscription instanceof EmailSubscriber) {

                if ($subscription->getEmailNotification()->getIsEnable() == EmailNotification::ENABLED) {

                    $to = $subscription->getEmail();

                    $content    =   file_get_contents(sfConfig::get('sf_root_dir')."/apps/orangehrm/templates/mail/".self::EMAIL_TEMPLATE_REVIWER_SUBMIT);
                    $varibles   =   array('#reviwer'=>$performanceReview->getReviewer()->getFirstName().' '.$performanceReview->getReviewer()->getLastName(),
                                          '#employee'=>$performanceReview->getEmployee()->getFirstName().' '.$performanceReview->getEmployee()->getLastName());
                    $mailBody   =   strtr($content, $varibles);
                    $mailService    = new EmailService();
                    $mailService->setMessageTo(array($to));
                    $mailService->setMessageSubject("Performance Review Submitted");
                    $mailService->setMessageBody($mailBody);
                    @$mailService->sendEmail();

                }

            }
	}
            return true;

        } catch( Exception $e) {
            throw new AdminServiceException($e->getMessage());
        }
    }

    /**
     * Send Reviwer reject email
     * PerformanceReview $performanceReview
     * @return boolean
     */
    public function sendReviwRejectEmail( PerformanceReview $performanceReview ){
        try{

            $email      =   $performanceReview->getReviewer()->getEmpWorkEmail();
            if ( $email != '') {

                $content    =   file_get_contents(sfConfig::get('sf_root_dir')."/apps/orangehrm/templates/mail/".self::EMAIL_TEMPLATE_HRADMIN_REJECT);
                $varibles   =   array('#comments'=>$performanceReview->getLatestComment(),
                                      '#employee'=>$performanceReview->getEmployee()->getFirstName().' '.$performanceReview->getEmployee()->getLastName());
                $mailBody   =   strtr($content, $varibles);

                $mailService    = new EmailService();
                $mailService->setMessageTo(array($email));
                $mailService->setMessageSubject("Performance Review Rejected");
                $mailService->setMessageBody($mailBody);
                @$mailService->sendEmail();
            }
            return true ;
        } catch (Exception $e) {
            throw new AdminServiceException($e->getMessage());
        }
    }

    /**
     * Send Reviwer approve email
     * PerformanceReview $performanceReview
     * @return boolean
     */
    public function sendReviwApproveEmail( PerformanceReview $performanceReview ){
      try{

        $email      =   $performanceReview->getReviewer()->getEmpWorkEmail();
        if ( $email != '') {
            $content    =   file_get_contents(sfConfig::get('sf_root_dir')."/apps/orangehrm/templates/mail/".self::EMAIL_TEMPLATE_HRADMIN_APPROVE);
            $varibles   =   array('#comments'=>$performanceReview->getLatestComment(),
                                  '#employee'=>$performanceReview->getEmployee()->getFirstName().' '.$performanceReview->getEmployee()->getLastName());
            $mailBody   =   strtr($content, $varibles);

            $mailService    = new EmailService();
            $mailService->setMessageTo(array($email));
            $mailService->setMessageSubject("Performance Review Approved");
            $mailService->setMessageBody($mailBody);
            @$mailService->sendEmail();
        }
            return true ;
        } catch ( Exception $e) {
            throw new AdminServiceException($e->getMessage());
        }
    }

    /**
     * Sends an email to reviewer when a Performance Review is added.
     *
     * @param PerformanceReviewService $review
     * @return null
     */

    public function informReviewer(PerformanceReview $review) {

        try {

            $reviewerEmail = $review->getReviewer()->getEmpWorkEmail();

            if ($reviewerEmail != '') {

                $content = file_get_contents(sfConfig::get('sf_root_dir')."/apps/orangehrm/modules/performance/templates/email/".self::EMAIL_TEMPLATE_ADD_REVIEW);
                $varibles = array('#reviewerName'=> $review->getReviewer()->getFirstName(),
                                  '#empName' => $review->getEmployee()->getFullName(),
                                  '#period' => set_datepicker_date_format($review->getPeriodFrom()).' '.set_datepicker_date_format($review->getPeriodTo()),
                                  '#dueDate' => set_datepicker_date_format($review->getDueDate()));
                $mailBody = strtr($content, $varibles);

                $mailService = new EmailService();
                $mailService->setMessageTo(array($reviewerEmail));
                $mailService->setMessageFrom(array("admin@orangehrm"));
                $mailService->setMessageSubject("You Have Been Assigned a New Performance Review");
                $mailService->setMessageBody($mailBody);
                @$mailService->sendEmail();
            }
            return true;
        } catch (Exception $e) {
            throw new PerformanceServiceException($e->getMessage());
        }

    }

    /**
     * Checks whether the given employee is a reviewer
     */

    public function isReviewer($empId) {

        try {
            $reviews = $this->performanceReviewDao->searchPerformanceReview(array('reviewerId' => $empId));

            if (count($reviews) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw new PerformanceServiceException($e->getMessage());
        }

    }

    /**
     * Get reviewee list of given reviewer as json
     * If $addSelf is true then reviewer's details
     * will also be added.
     */
    public function getRevieweeListAsJson($reviewerId, $addSelf = false) {

        try {

            $resultList = $this->performanceReviewDao->searchPerformanceReview(array('reviewerId' => $reviewerId));

            $empList = array();
            $empIds = array();

            /* Making sure employee list is unique: Begins */
            $i = 0;
            $escapeCharSet = array(38, 39, 34, 60, 61,62, 63, 64, 58, 59, 94, 96);
            foreach ($resultList as $result) {
            	
            	$empId =  $result->getEmployee()->getEmpNumber();
            	
            	if (!in_array($empId, $empIds)) {
 	            	$empList[$i][0] = $result->getEmployee()->getFirstName() . " " . $result->getEmployee()->getLastName();
	                $empList[$i][1] = $empId;
	                $empIds[] = $empId;
 	               	$i++;
            	}
            	
            }
            /* Making sure employee list is unique: Ends */

            $jsonList = array();

            foreach ($empList as $emp) {
               foreach($escapeCharSet as $char) {
                  $emp[0] = str_replace(chr($char), (chr(92) . chr($char)), $emp[0]);
               }
                $jsonList[$emp[1]] = array('name' => $emp[0], 'id' => $emp[1]);
            }

            if ($addSelf) {
               $name = $resultList[0]->getReviewer()->getFirstName() . " " . $resultList[0]->getReviewer()->getLastName();
                  foreach($escapeCharSet as $char) {
                      $name = str_replace(chr($char), (chr(92) . chr($char)), $name);
                  }
                  $id = $resultList[0]->getReviewer()->getEmpNumber();
                  $jsonList[$id] = array('name' => $name, 'id' => $resultList[0]->getReviewer()->getEmpNumber());
            }

            $jsonString = json_encode($jsonList);

            return $jsonString;

        } catch (Exception $e) {
            throw new PerformanceServiceException($e->getMessage());
        }

    }
}
