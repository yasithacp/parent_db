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
 * @group Pim
 */
class EmployeeServiceTest extends PHPUnit_Framework_TestCase {

    private $testCase;
    private $employeeDao;
    private $employeeService;
    private $fixture;

    /**
     * Set up method
     */
    protected function setUp() {
        $this->testCase = sfYaml::load(sfConfig::get('sf_plugins_dir') . '/orangehrmPimPlugin/test/fixtures/employee.yml');
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmPimPlugin/test/fixtures/EmployeeDao.yml';
        $this->employeeService = new EmployeeService();
    }
    
    public function testGetSetEmployeeDao() {
        $mockDao = $this->getMock('EmployeeDao');
                
        $this->employeeService->setEmployeeDao($mockDao);
        $this->assertEquals($mockDao, $this->employeeService->getEmployeeDao());        
    }

    /**
     * Testing addEmployee
     */
    public function testAddEmployee() {
        foreach ($this->testCase['Employee'] as $k => $v) {
            $employee = new Employee();
            $employee->setLastName($v['lastName']);
            $employee->setFirstName($v['firstName']);

            $employeeDao = $this->getMock('EmployeeDao');
            $employeeDao->expects($this->once())
                    ->method('addEmployee')
                    ->will($this->returnValue(true));
            $this->employeeService->setEmployeeDao($employeeDao);
            $result = $this->employeeService->addEmployee($employee);
            $this->assertTrue($result);
        }
    }

    /**
     * Testing addEmployee Exception
     */
    public function testAddEmployeeException() {
        $employee = new Employee();
        $employee->setLastName('Last Name');
        $employee->setFirstName('First Name');

        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('addEmployee')
                ->will($this->throwException(new DaoException()));

        $this->employeeService->setEmployeeDao($employeeDao);

        try {
            $result = $this->employeeService->addEmployee($employee);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }
    }


    /**
     * Testing GetEmployee
     */
    public function testGetEmployee() {
        
        $empNumber = 12;
        $employee = new Employee();
        $employee->setLastName('Last Name');
        $employee->setFirstName('First Name');
        $employee->setEmpNumber($empNumber);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
             ->method('getEmployee')
             ->with($empNumber)
             ->will($this->returnValue($employee));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $retVal = $this->employeeService->getEmployee($empNumber);
        
        $this->assertEquals($employee, $retVal);
        
        // Test Exception
        $mockDao =  $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
             ->method('getEmployee')
             ->with($empNumber)
             ->will($this->throwException(new DaoException()));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->getEmployee($empNumber);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }
    }
    
    
    /**
     * Testing Adding Employee Picture
     */
    public function testAddEmployeePicture() {
        foreach ($this->testCase['Employee'] as $k => $v) {
            $this->employeeDao = $this->getMock('EmployeeDao');
            $this->employeeDao->expects($this->once())
                    ->method('saveEmployeePicture')
                    ->will($this->returnValue(true));
            $this->employeeService->setEmployeeDao($this->employeeDao);

            $pic = new EmpPicture();
            $pic->setEmpNumber($v['id']);
            $pic->setFilename("pic_" . rand(0, 1000));
            $result = $this->employeeService->saveEmployeePicture($pic);
            $this->assertTrue($result);
        }
    }

    /**
     * Testing Adding Employee Picture
     */
    public function testAddEmployeePictureException() {
        
        $empNumber = 102;
        
        $pic = new EmpPicture();
        $pic->setEmpNumber($empNumber);
        $pic->setFilename("pic_" . rand(0, 1000));        
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('saveEmployeePicture')
                 ->with($pic)
                 ->will($this->throwException(new DaoException()));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->saveEmployeePicture($pic);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        } 
    }
    
    /**
     * Testing readEmployeePicture
     */
    public function testReadEmployeePicture() {
        foreach ($this->testCase['Employee'] as $k => $v) {
            $this->employeeDao = $this->getMock('EmployeeDao');
            $this->employeeDao->expects($this->once())
                    ->method('readEmployeePicture')
                    ->will($this->returnValue(new EmpPicture()));
            $this->employeeService->setEmployeeDao($this->employeeDao);
            $pic = $this->employeeService->readEmployeePicture($v['id']);
            $this->assertTrue($pic instanceof EmpPicture);
        }
    }
    
    /**
     * Testing readEmployeePicture
     */
    public function testReadEmployeePictureException() {
        
        $empNumber = 102;    
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('readEmployeePicture')
                 ->with($empNumber)
                 ->will($this->throwException(new DaoException()));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->readEmployeePicture($empNumber);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }        
    }    

    /**
     * Testing getPicture
     */
    public function testGetPicture() {
        $picture = 'askd;sadjf';
        $empNumber = 121;
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getPicture')
                 ->with($empNumber)
                 ->will($this->returnValue($picture));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getPicture($empNumber);
        $this->assertEquals($picture, $result);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getPicture')
                 ->with($empNumber)
                 ->will($this->throwException(new DaoException()));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->getPicture($empNumber);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }               
    }
    
    /**
     * Testing deletePhoto
     */
    public function testDeletePhoto() {
        foreach ($this->testCase['Employee'] as $k => $v) {
            $this->employeeDao = $this->getMock('EmployeeDao');
            $this->employeeDao->expects($this->once())
                    ->method('deletePhoto')
                    ->will($this->returnValue(true));
            $this->employeeService->setEmployeeDao($this->employeeDao);
            $result = $this->employeeService->deletePhoto($v['id']);
            $this->assertTrue($result);
        }
    }

    public function testDeletePhotoException() {
        $empNumber = 102;
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deletePhoto')
                 ->with($empNumber)
                 ->will($this->throwException(new DaoException()));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->deletePhoto($empNumber);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }        
    }
    
    /**
     * Testing savePersonalDetails
     */
    public function testSavePersonalDetails() {
        $empNumber = 121;
        $employee = new Employee();
        $employee->setLastName('Last Name');
        $employee->setFirstName('First Name');
        $employee->setMiddleName('M');
        $employee->setEmpNumber($empNumber);                             
        $employee->setNickName('AB');
        $employee->setOtherId('e2192');
        
        $isEss = true;
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('savePersonalDetails')
                 ->with($employee, $isEss)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->savePersonalDetails($employee, $isEss);
        $this->assertTrue($result);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('savePersonalDetails')
                 ->with($employee, $isEss)
                 ->will($this->throwException(new DaoException()));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->savePersonalDetails($employee, $isEss);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }               
    }
    
    /**
     * Testing saveContactDetails
     */
    public function testSaveContactDetails() {
        $empNumber = 121;
        $employee = new Employee();
        $employee->setLastName('Last Name');
        $employee->setFirstName('First Name');
        $employee->setStreet1('Main Street');
        $employee->setStreet2('Suite 299');
        $employee->setCity('Houston');
        $employee->setProvince('Texas');
        $employee->setEmpZipcode('928282');
        $employee->setEmpHmTelephone('2998288288');
        $employee->setEmpMobile('28882882');
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('saveContactDetails')
                 ->with($employee)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->saveContactDetails($employee);
        $this->assertTrue($result);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('saveContactDetails')
                 ->with($employee)
                 ->will($this->throwException(new DaoException()));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->saveContactDetails($employee);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }               
    }
    
    /**
     * Testing deleteEmergencyContacts
     */
    public function testDeleteEmergencyContacts() {
        $empNumber = 111;
        $contactsToDelete = array('1', '2', '4');
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteEmergencyContacts')
                 ->with($empNumber, $contactsToDelete)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->deleteEmergencyContacts($empNumber, $contactsToDelete);
        $this->assertTrue($result);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteEmergencyContacts')
                 ->with($empNumber, $contactsToDelete)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->deleteEmergencyContacts($empNumber, $contactsToDelete);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }               
    }

    /**
     * Testing deleteImmigration
     */
    public function testDeleteImmigration() {
        $empNumber = 111;
        $immigrationToDelete = array('1', '2', '4');
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteImmigration')
                 ->with($empNumber, $immigrationToDelete)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->deleteImmigration($empNumber, $immigrationToDelete);
        $this->assertTrue($result);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteImmigration')
                 ->with($empNumber, $immigrationToDelete)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->deleteImmigration($empNumber, $immigrationToDelete);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }               
    }
     
    /**
     * Testing getDependents
     */
    public function testGetDependents() {
        $empNumber = 111;
        $dependents = array();
        $dependent = new EmpDependent();
        $dependent->setEmpNumber(111);
        $dependent->setSeqno(1);
        $dependent->setName('Anthony Perera');
        $dependent->setRelationshipType('child');
        
        $dependents[0] = $dependent;

        $dependent = new EmpDependent();
        $dependent->setEmpNumber(111);
        $dependent->setSeqno(2);
        $dependent->setName('Anton Perera');
        $dependent->setRelationshipType('child');
        
        $dependents[1] = $dependent;
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getDependents')
                 ->with($empNumber)
                 ->will($this->returnValue($dependents));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getDependents($empNumber);
        $this->assertEquals($dependents, $result);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getDependents')
                 ->with($empNumber)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->getDependents($empNumber);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }               
    }
    
    /**
     * Testing deleteDependents
     */
    public function testDeleteDependents() {
        $empNumber = 111;
        $entriesToDelete = array('1', '2', '4');
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteDependents')
                 ->with($empNumber, $entriesToDelete)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->deleteDependents($empNumber, $entriesToDelete);
        $this->assertTrue($result);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteDependents')
                 ->with($empNumber, $entriesToDelete)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->deleteDependents($empNumber, $entriesToDelete);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }               
    }
    
    /**
     * Testing deleteChildren
     */
    public function testDeleteChildren() {
        $empNumber = 111;
        $entriesToDelete = array('1', '2', '4');
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteChildren')
                 ->with($empNumber, $entriesToDelete)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->deleteChildren($empNumber, $entriesToDelete);
        $this->assertTrue($result);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteChildren')
                 ->with($empNumber, $entriesToDelete)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->deleteChildren($empNumber, $entriesToDelete);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }               
    }
    
    /**
     * Testing isSupervisor
     */
    public function testIsSupervisor() {
        $empNumber = 111;
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('isSupervisor')
                 ->with($empNumber)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->isSupervisor($empNumber);
        $this->assertTrue($result);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('isSupervisor')
                 ->with($empNumber)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->isSupervisor($empNumber);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }               
    }
    
    /**
     * Testing saveWorkExperience
     */
    public function testSaveWorkExperience() {
        $empNumber = 121;
        $experience = new EmpWorkExperience();
        $experience->setEmpNumber($empNumber);
        $experience->setSeqno(1);
        $experience->setEmployer('ACME Inc');
        $experience->setJobtitle('Manager');
        
        $isEss = true;
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('saveWorkExperience')
                 ->with($experience)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->saveWorkExperience($experience);
        $this->assertTrue($result);              
    }    
    
    /**
     * Testing getWorkExperience
     */
    public function testGetWorkExperience() {
        $empNumber = 121;
        $sequence = 1;
        
        $experience = new EmpWorkExperience();
        $experience->setEmpNumber($empNumber);
        $experience->setSeqno(1);
        $experience->setEmployer('ACME Inc');
        $experience->setJobtitle('Manager');
        
        $isEss = true;
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getWorkExperience')
                 ->with($empNumber, $sequence)
                 ->will($this->returnValue($experience));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getWorkExperience($empNumber, $sequence);
        $this->assertEquals($experience, $result);              
    } 
    
    /**
     * Testing deleteWorkExperience
     */
    public function testDeleteWorkExperience() {
        $empNumber = 111;
        $entriesToDelete = array('1', '2', '4');
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteWorkExperience')
                 ->with($empNumber, $entriesToDelete)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->deleteWorkExperience($empNumber, $entriesToDelete);
        $this->assertTrue($result);              
    }
    
    /**
     * Testing saveEducation
     */
    public function testSaveEducation() {
        $empNumber = 121;
        $education = new EmployeeEducation();
        $education->setEmpNumber($empNumber);
        $education->setEducationId(1);
        $education->setMajor('Engineering');
        $education->setYear('2000');
        $education->setScore('3.2');       
       
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('saveEducation')
                 ->with($education)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->saveEducation($education);
        $this->assertTrue($result);              
    }    
    
    /**
     * Testing getEducation
     */
    public function testGetEducation() {
        
        $education = new EmployeeEducation();
        $education->setId(1);
        $education->setEmpNumber(121);
        $education->setEducationId(1);
        $education->setMajor('Engineering');
        $education->setYear('2000');
        $education->setScore('3.2');  
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getEducation')
                 ->with(1)
                 ->will($this->returnValue($education));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getEducation(1);
        $this->assertEquals($education, $result);              
    } 
    
    /**
     * Testing deleteEducation
     */
    public function testDeleteEducation() {
        $empNumber = 111;
        $entriesToDelete = array('1', '2', '4');
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteEducation')
                 ->with($empNumber, $entriesToDelete)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->deleteEducation($empNumber, $entriesToDelete);
        $this->assertTrue($result);              
    }
    
    /**
     * Testing saveSkill
     */
    public function testSaveSkill() {
        $empNumber = 121;
        $skill = new EmployeeSkill();
        $skill->setEmpNumber($empNumber);
        $skill->setSkillId(2);
        $skill->setYearsOfExp(2);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('saveSkill')
                 ->with($skill)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->saveSkill($skill);
        $this->assertTrue($result);              
    }    
    
    /**
     * Testing getSkill
     */
    public function testGetSkill() {
        $empNumber = 121;
        $skillCode = 'SKI002';
        
        $skill = new EmployeeSkill();
        $skill->setEmpNumber($empNumber);
        $skill->setSkillId(2);
        $skill->setYearsOfExp(2);
        
        $isEss = true;
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getSkill')
                 ->with($empNumber, $skillCode)
                 ->will($this->returnValue($skill));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getSkill($empNumber, $skillCode);
        $this->assertEquals($skill, $result);              
    } 
    
    /**
     * Testing deleteSkill
     */
    public function testDeleteSkill() {
        $empNumber = 111;
        $entriesToDelete = array('1', '2', '4');
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteSkill')
                 ->with($empNumber, $entriesToDelete)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->deleteSkill($empNumber, $entriesToDelete);
        $this->assertTrue($result);              
    }
    
    /**
     * Testing getSalary
     */
    public function testGetSalary() {
        $empNumber = 121;
        $id = 1;
        
        $salary = new EmpBasicsalary();
        $salary->setEmpNumber($empNumber);
        $salary->setId($id);
        $salary->setSalaryComponent('Travel Expenses');
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getSalary')
                 ->with($empNumber, $id)
                 ->will($this->returnValue($salary));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getSalary($empNumber, $id);
        $this->assertEquals($salary, $result);              
    } 
    
    /**
     * Testing saveLanguage
     */
    public function testSaveLanguage() {
        $empNumber = 121;
        $language = new EmployeeLanguage();
        $language->setEmpNumber($empNumber);
        $language->setLangId(2);
        $language->setFluency(1);
        $language->setCompetency(1);
        $language->setComments('no comments'); 
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('saveLanguage')
                 ->with($language)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->saveLanguage($language);
        $this->assertTrue($result);              
    }    
    
    /**
     * Testing getLanguage
     */
    public function testGetLanguage() {
        $empNumber = 121;
        
        $language = new EmployeeLanguage();
        $language->setEmpNumber($empNumber);
        $language->setLangId(2);
        $language->setFluency(1);
        $language->setCompetency(1);
        $language->setComments('no comments');        
        
        $languageCode = 2;
        $langType = 1;
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getLanguage')
                 ->with($empNumber, $languageCode, $langType)
                 ->will($this->returnValue($language));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getLanguage($empNumber, $languageCode, $langType);
        $this->assertEquals($language, $result);              
    } 
    
    /**
     * Testing deleteLanguage
     */
    public function testDeleteLanguage() {
        $empNumber = 111;
        $entriesToDelete = array('1', '2', '4');
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteLanguage')
                 ->with($empNumber, $entriesToDelete)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->deleteLanguage($empNumber, $entriesToDelete);
        $this->assertTrue($result);              
    }
    
    /**
     * Testing saveLicense
     */
    public function testSaveLicense() {
        $empNumber = 121;
        $license = new EmployeeLicense();
        $license->setEmpNumber($empNumber);
        $license->setLicenseId(2);
        $license->setLicenseNo('199919');
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('saveLicense')
                 ->with($license)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->saveLicense($license);
        $this->assertTrue($result);              
    }    
    
    /**
     * Testing getLicense
     */
    public function testGetLicense() {
        $empNumber = 121;
        $licenseCode = 2;
        
        $license = new EmployeeLicense();
        $license->setEmpNumber($empNumber);
        $license->setLicenseId($licenseCode);
        $license->setLicenseNo('199919');        
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getLicense')
                 ->with($empNumber, $licenseCode)
                 ->will($this->returnValue($license));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getLicense($empNumber, $licenseCode);
        $this->assertEquals($license, $result);              
    } 
    
    /**
     * Testing deleteLicense
     */
    public function testDeleteLicense() {
        $empNumber = 111;
        $entriesToDelete = array('1', '2', '4');
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteLicense')
                 ->with($empNumber, $entriesToDelete)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->deleteLicense($empNumber, $entriesToDelete);
        $this->assertTrue($result);              
    } 
    
    /**
     * Testing getAttachments
     */
    public function testGetAttachments() {
        $empNumber = 121;
        $screen = 'personal';
        
        $attachments = array();
        foreach ($this->testCase['EmployeeAttachment'] as $values ) {
            $attachment = new EmployeeAttachment();
            $attachment->fromArray($values);
            $attachments[] = $attachment;
        }             
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getAttachments')
                 ->with($empNumber, $screen)
                 ->will($this->returnValue($attachments));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getAttachments($empNumber, $screen);
        $this->assertEquals($attachments, $result);              
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getAttachments')
                 ->with($empNumber, $screen)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->getAttachments($empNumber, $screen);
            $this->fail("Exception expected");
        } catch (Exception $e) {
        }  
        
    } 
    
    /**
     * Testing deleteAttachments
     */
    public function testDeleteAttachments() {
        $empNumber = 111;
        $entriesToDelete = array('1', '2', '4');
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteAttachments')
                 ->with($empNumber, $entriesToDelete)
                 ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->deleteAttachments($empNumber, $entriesToDelete);
        $this->assertTrue($result);              
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteAttachments')
                 ->with($empNumber, $entriesToDelete)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->deleteAttachments($empNumber, $entriesToDelete);
            $this->fail("Exception expected");
        } catch (Exception $e) {
        }         
    }
    
    /**
     * Testing getAttachment
     */
    public function testGetAttachment() {
        $empNumber = 121;
        
        $attachments = array();
        foreach ($this->testCase['EmployeeAttachment'] as $values ) {
            $attachment = new EmployeeAttachment();
            $attachment->fromArray($values);
            $attachments[] = $attachment;
        }             
        
        $attachment = $attachments[0];
        $attachmentId = $attachment->getAttachId();
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getAttachment')
                 ->with($empNumber, $attachmentId)
                 ->will($this->returnValue($attachment));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getAttachment($empNumber, $attachmentId);
        $this->assertEquals($attachment, $result);              
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getAttachment')
                 ->with($empNumber, $attachmentId)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->getAttachment($empNumber, $attachmentId);
            $this->fail("Exception expected");
        } catch (Exception $e) {
        }  
        
    }
    
    /**
     * Testing getEmployeeList
     */
    public function testGetEmployeeList() {
        $sortField = 'empNumber';
        $orderBy = 'DESC';
        
        $employees = array();
        foreach ($this->testCase['Employee'] as $values ) {
            $employee = new Employee();
            $employee->fromArray($values);
            $employees[] = $employee;
        }             
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getEmployeeList')
                 ->with($sortField, $orderBy)
                 ->will($this->returnValue($employees));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getEmployeeList($sortField, $orderBy);
        $this->assertEquals($employees, $result);              
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getEmployeeList')
                 ->with($sortField, $orderBy)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->getEmployeeList($sortField, $orderBy);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }           
    }
    
    /**
     * Testing getSupervisorList
     */
    public function testGetSupervisorList() {
        $sortField = 'empNumber';
        $orderBy = 'DESC';
        
        $supervisors = array();
        foreach ($this->testCase['Employee'] as $values ) {
            $employee = new Employee();
            $employee->fromArray($values);
            $supervisors[] = $employee;
        }             
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getSupervisorList')
                 ->will($this->returnValue($supervisors));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getSupervisorList();
        $this->assertEquals($supervisors, $result);              
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getSupervisorList')
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->getSupervisorList();
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }           
    }
    
    /**
     * Testing searchEmployee
     */
    public function testSearchEmployee() {
        $field = 'empNumber';
        $value = '2';
        
        $employees = array();
        foreach ($this->testCase['Employee'] as $values ) {
            $employee = new Employee();
            $employee->fromArray($values);
            $employees[] = $employee;
        }             
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('searchEmployee')
                 ->with($field, $value)
                 ->will($this->returnValue($employees));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->searchEmployee($field, $value);
        $this->assertEquals($employees, $result);              
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('searchEmployee')
                 ->with($field, $value)                
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->searchEmployee($field, $value);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }           
    }    
    
    /**
     * Testing getEmployeeCount
     */
    public function testGetEmployeeCount() {

        $count = 212;         
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getEmployeeCount')
                 ->will($this->returnValue($count));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getEmployeeCount();
        $this->assertEquals($count, $result);              
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getEmployeeCount')               
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->getEmployeeCount();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }           
    }    
    
    /**
     * Testing getSupervisorEmployeeList
     */
    public function testGetSupervisorEmployeeList() {
        $supervisorId = '11';
        
        $employees = array();
        foreach ($this->testCase['Employee'] as $values ) {
            $employee = new Employee();
            $employee->fromArray($values);
            $employees[] = $employee;
        }             
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getSupervisorEmployeeList')
                 ->with($supervisorId)
                 ->will($this->returnValue($employees));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getSupervisorEmployeeList($supervisorId);
        $this->assertEquals($employees, $result);              
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getSupervisorEmployeeList')
                 ->with($supervisorId)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->getSupervisorEmployeeList($supervisorId);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }           
    }
    
    /**
     * Testing getEmployeeListAsJson
     */
    public function testGetEmployeeListAsJson() {

        $employees = array();
        foreach ($this->testCase['EmployeeJson'] as $values ) {
            $employees[] = $values;
        } 
        
        $workShift = true;
        
        $jsonStr = json_encode($employees);

        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                ->method('getEmployeeListAsJson')
                ->with($workShift)
                ->will($this->returnValue($jsonStr));
        $this->employeeService->setEmployeeDao($mockDao);
        $result = $this->employeeService->getEmployeeListAsJson($workShift);
        $this->assertEquals($jsonStr, $result);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                ->method('getEmployeeListAsJson')
                ->with($workShift)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->getEmployeeListAsJson($workShift);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }         
    }

    /**
     * Testing getSupervisorEmployeeChain
     */
    public function testGetSupervisorEmployeeChain() {
        $supervisorId = '11';
        
        $employees = array();
        foreach ($this->testCase['Employee'] as $values ) {
            $employee = new Employee();
            $employee->fromArray($values);
            $employees[] = $employee;
        }             
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getSupervisorEmployeeChain')
                 ->with($supervisorId)
                 ->will($this->returnValue($employees));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->getSupervisorEmployeeChain($supervisorId);
        $this->assertEquals($employees, $result);              
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getSupervisorEmployeeChain')
                 ->with($supervisorId)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->getSupervisorEmployeeChain($supervisorId);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }           
    }
    
   /**
     * Testing filterEmployeeListBySubUnit
     */
    public function testFilterEmployeeListBySubUnit() {
        $subUnitId = 12;    
        
        $employees = array();
        foreach ($this->testCase['Employee'] as $values ) {
            $employee = new Employee();
            $employee->fromArray($values);
            $employees[] = $employee;
        }             
        
        $employees[0]->setWorkStation($subUnitId);
        
        // Search by specific sub unit
        $result = $this->employeeService->filterEmployeeListBySubUnit($employees, $subUnitId);
        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));
        $this->assertEquals($employees[0], $result[0]);
        
        // Search by Root - Should return all employees in list
        $result = $this->employeeService->filterEmployeeListBySubUnit($employees, 1);
        $this->assertEquals($employees, $result);
        
        // Empty subunit Id should be the same as root 
        $result = $this->employeeService->filterEmployeeListBySubUnit($employees, NULL);
        $this->assertEquals($employees, $result);
        
        // If no employee list passed, will get all employees from dao adn filter        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getEmployeeList')
                 ->will($this->returnValue($employees));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->filterEmployeeListBySubUnit(NULL, $subUnitId);
        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));
        $this->assertEquals($employees[0], $result[0]);           
        
        // Dao Exception
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('getEmployeeList')
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->filterEmployeeListBySubUnit(NULL, $subUnitId);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }           
    }
    
    
    /**
     * Testing deleteEmployee
     */
    public function testDeleteEmployee() {
                
        $employeesToDelete = array('1', '2', '4');
        $numEmployees = count($employeesToDelete);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteEmployee')
                 ->with($employeesToDelete)
                 ->will($this->returnValue($numEmployees));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $result = $this->employeeService->deleteEmployee($employeesToDelete);
        $this->assertEquals($numEmployees, $result);              
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                 ->method('deleteEmployee')
                 ->with($employeesToDelete)
                 ->will($this->throwException(new DaoException()));
               
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $result = $this->employeeService->deleteEmployee($employeesToDelete);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }
        
    }

    /**
     * Testing isEmployeeIdInUse
     */
    public function testIsEmployeeIdInUse() {

        $employeeId = 'E199';
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                ->method('isEmployeeIdInUse')
                ->with($employeeId)
                ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        $result = $this->employeeService->isEmployeeIdInUse($employeeId);
        $this->assertTrue($result);               
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                ->method('isEmployeeIdInUse')
                ->with($employeeId)
                ->will($this->throwException(new DaoException()));
        
        $this->employeeService->setEmployeeDao($mockDao);       
        
        try {
            $result = $this->employeeService->isEmployeeIdInUse($employeeId);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }
        
    }
    
    
    /**
     * Testing checkForEmployeeWithSameName
     */
    public function testCheckForEmployeeWithSameName() {

        $first = 'John';
        $middle = 'A';
        $last = 'Kennedy';
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                ->method('checkForEmployeeWithSameName')
                ->with($first, $middle, $last)
                ->will($this->returnValue(true));
        
        $this->employeeService->setEmployeeDao($mockDao);
        $result = $this->employeeService->checkForEmployeeWithSameName($first, $middle, $last);
        $this->assertTrue($result);               
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
                ->method('checkForEmployeeWithSameName')
                ->with($first, $middle, $last)
                ->will($this->throwException(new DaoException()));
        
        $this->employeeService->setEmployeeDao($mockDao);       
        
        try {
            $result = $this->employeeService->checkForEmployeeWithSameName($first, $middle, $last);
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof PIMServiceException);
        }
        
    }
    
    /**
     * Test GetEmergencyContacts
     */
    public function testGetEmergencyContacts() {

        // TODO: Load from fixture
        $contacts = array();
        $contacts[0] = new EmpEmergencyContact();
        $contacts[1] = new EmpEmergencyContact();

        $empNumber = 2;

        $emergencyContactList = array();

        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('getEmergencyContacts')
                ->with($empNumber)
                ->will($this->returnValue($contacts));

        $this->employeeService->setEmployeeDao($employeeDao);

        $emgContacts = $this->employeeService->getEmergencyContacts($empNumber);
        $this->assertEquals(count($contacts), count($emgContacts));
        $this->assertEquals($emgContacts, $contacts);


        // Test exception
        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('getEmergencyContacts')
                ->with($empNumber)
                ->will($this->throwException(new DaoException('test')));

        $this->employeeService->setEmployeeDao($employeeDao);

        try {
            $emgContacts = $this->employeeService->getEmergencyContacts($empNumber);
            $this->fail('DaoException expected');
        } catch (PIMServiceException $e) {
            // expected
        }
    }

    /**
     * Test SaveEmployeePassport
     */
    public function testSaveEmployeePassport() {

        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('saveEmployeePassport')
                ->will($this->returnValue(true));

        $this->employeeService->setEmployeeDao($employeeDao);
        $empPassport = new EmpPassPort();
        $empPassport->setEmpNumber(1);
        $result = $this->employeeService->saveEmployeePassport($empPassport);
        $this->assertTrue($result);
    }

    /**
     * Test saving getEmployeePassport returns object
     */
    public function testGetEmployeePassport() {

        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('getEmployeePassport')
                ->will($this->returnValue(new EmpPassport()));

        $this->employeeService->setEmployeeDao($employeeDao);

        $readEmpPassport = $this->employeeService->getEmployeePassport(1);
        $this->assertTrue($readEmpPassport instanceof EmpPassport);
    }

    /**
     * Test getEmployeeTax returns object
     */
    public function testGetEmployeeTaxExemptions() {

        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('getEmployeeTaxExemptions')
                ->will($this->returnValue(new EmpUsTaxExemption()));

        $this->employeeService->setEmployeeDao($employeeDao);

        $readEmpTaxExemption = $this->employeeService->getEmployeeTaxExemptions(1);
        $this->assertTrue($readEmpTaxExemption instanceof EmpUsTaxExemption);
    }

    /**
     * Test SaveEmployeeTaxExemptions
     */
    public function testSaveEmployeeTaxExemptions() {

        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('saveEmployeeTaxExemptions')
                ->will($this->returnValue(true));

        $this->employeeService->setEmployeeDao($employeeDao);

        $empUsTaxExemption = new EmpUsTaxExemption();
        $empUsTaxExemption->setEmpNumber(3);
        $result = $this->employeeService->saveEmployeeTaxExemptions($empUsTaxExemption);
        $this->assertTrue($result);
    }

    /**
     * Test Supervisor Report-To list for a given employee
     */
    public function testGetSupervisorListForEmployee() {

        $empNumber = 3;

        $reportToSupervisorList = TestDataService::loadObjectList('ReportTo', $this->fixture, 'ReportTo');
        $reportToSupervisorList1 = array($reportToSupervisorList[0], $reportToSupervisorList[1]);
        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('getSupervisorListForEmployee')
                ->with($empNumber)
                ->will($this->returnValue($reportToSupervisorList1));

        $this->employeeService->setEmployeeDao($employeeDao);

        $readReportToSupervisorList1 = $this->employeeService->getSupervisorListForEmployee($empNumber);
        $this->assertTrue($readReportToSupervisorList1[0] instanceof ReportTo);
    }

    /**
     * Test Subordiate Report-To list for a given employee
     */
    public function testGetSubordinateListForEmployee() {

        $empNumber = 3;

        $reportToSubordinateList = TestDataService::loadObjectList('ReportTo', $this->fixture, 'ReportTo');
        $reportToSubordinateListList1 = array($reportToSubordinateList[2], $reportToSubordinateList[3]);
        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('getSubordinateListForEmployee')
                ->with($empNumber)
                ->will($this->returnValue($reportToSubordinateListList1));

        $this->employeeService->setEmployeeDao($employeeDao);

        $readReportToSubordinateList1 = $this->employeeService->getSubordinateListForEmployee($empNumber);
        $this->assertTrue($readReportToSubordinateList1[0] instanceof ReportTo);
        $this->assertTrue($readReportToSubordinateList1[1] instanceof ReportTo);
    }

    /**
     * Test get Report-To object for a given supervisor subordinate and reporting method
     */
    public function testGetReportToObject() {

        $reportingMode = 4;
        $supNumber = 4;
        $subNumber = 3;

        $reportToObjectList = TestDataService::loadObjectList('ReportTo', $this->fixture, 'ReportTo');

        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('getReportToObject')
                ->with($supNumber, $subNumber)
                ->will($this->returnValue($reportToObjectList[0]));

        $this->employeeService->setEmployeeDao($employeeDao);

        $readReportToObject = $this->employeeService->getReportToObject($supNumber, $subNumber);
        $this->assertTrue($readReportToObject instanceof ReportTo);
    }

    /**
     * Test delete Report-To object list for a given string array containing supervisor subordinate and reporting method
     */
    public function testDeleteReportToObject() {

        $supNumber = 4;
        $subNumber = 3;
        $reportingMode = 4;

        $supOrSubListToDelete = array("4 3 4");

        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('deleteReportToObject')
                ->with($supNumber, $subNumber, $reportingMode)
                ->will($this->returnValue(true));

        $this->employeeService->setEmployeeDao($employeeDao);

        $result = $this->employeeService->deleteReportToObject($supOrSubListToDelete);
        $this->assertTrue($result);
    }

    /**
     * Test get membership details list for a given employee
     */
    public function testGetMembershipDetails() {

        $empNumber = 1;

        $membershipDetailList = TestDataService::loadObjectList('EmployeeMemberDetail', $this->fixture, 'EmployeeMemberDetail');

        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('getMembershipDetails')
                ->with($empNumber)
                ->will($this->returnValue($membershipDetailList));

        $this->employeeService->setEmployeeDao($employeeDao);

        $readMembershipDetailList = $this->employeeService->getMembershipDetails($empNumber);
        $this->assertTrue($readMembershipDetailList[0] instanceof EmployeeMemberDetail);
        $this->assertTrue($readMembershipDetailList[1] instanceof EmployeeMemberDetail);
    }

    /**
     * Test get membership detail object for a given employee membershipType and membership
     */
    public function testGetMembershipDetail() {

        $empNumber = 1;
        $membership = 1;

        $membershipDetailList = TestDataService::loadObjectList('EmployeeMemberDetail', $this->fixture, 'EmployeeMemberDetail');

        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('getMembershipDetail')
                ->with($empNumber)
                ->will($this->returnValue($membershipDetailList[0]));

        $this->employeeService->setEmployeeDao($employeeDao);

        $readMembershipDetail = $this->employeeService->getMembershipDetail($empNumber, $membership);
        $this->assertTrue($readMembershipDetail instanceof EmployeeMemberDetail);
    }

    /**
     * Test delete membership details collection list for a given string array containing empNumber membershipType and membership
     */
    public function testDeleteMembershipDetails() {

        $empNumber = 1;
        $membership = 1;

        $membershipsToDelete = array("1 1");

        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('deleteMembershipDetails')
                ->with($empNumber, $membership)
                ->will($this->returnValue(true));

        $this->employeeService->setEmployeeDao($employeeDao);

        $result = $this->employeeService->deleteMembershipDetails($membershipsToDelete);
        $this->assertTrue($result);
    }

    /**
     * Test get emergency contacts for a given employee
     */
    public function testGetEmergencyContactsusingFixture() {

        $empNumber = 1;

        $emergencyContactList = TestDataService::loadObjectList('EmpEmergencyContact', $this->fixture, 'EmpEmergencyContact');

        $employeeDao = $this->getMock('EmployeeDao');

        $employeeDao->expects($this->once())
                ->method('getEmergencyContacts')
                ->with($empNumber)
                ->will($this->returnValue($emergencyContactList));

        $this->employeeService->setEmployeeDao($employeeDao);
        $readEmergencyContactlList = $this->employeeService->getEmergencyContacts($empNumber);
        $this->assertTrue($readEmergencyContactlList[0] instanceof EmpEmergencyContact);
        $this->assertTrue($readEmergencyContactlList[1] instanceof EmpEmergencyContact);
    }
    
    /**
     * Test getEmployeeYearsOfService 
     */
    public function testGetEmployeeYearsOfService() {

        $empNumber = 12;
        $joinedDate = '2001-02-01';
        $currentDate = '2010-03-04';
        $employee = new Employee();
        $employee->setLastName('Last Name');
        $employee->setFirstName('First Name');
        $employee->setEmpNumber($empNumber);
        $employee->setJoinedDate($joinedDate);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
             ->method('getEmployee')
             ->with($empNumber)
             ->will($this->returnValue($employee));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        $yearsOfService = $this->employeeService->getEmployeeYearsOfService($empNumber, $currentDate);
        
        //$this->assertEquals(9, $yearsOfService);
        
        // Test with non-existant employee
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
             ->method('getEmployee')
             ->with($empNumber)
             ->will($this->returnValue(null));
        
        $this->employeeService->setEmployeeDao($mockDao);
        
        try {
            $yearsOfService = $this->employeeService->getEmployeeYearsOfService($empNumber, $currentDate);        
            $this->fail("PIMServiceException expected");
        } catch (PIMServiceException $e) {
            // Expected
        }
        
    }
    
    public function testGetEmailList() {
        
        $list = array(0 => array( 'empNo' => 1, 'workEmail' => 'kayla@xample.com', 'othEmail' => 'kayla2@xample.com' ));
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
             ->method('getEmailList')
             ->will($this->returnValue($list));
        
        $this->employeeService->setEmployeeDao($mockDao);
        $result = $this->employeeService->getEmailList();
        $this->assertEquals($list, $result);
    }
    
    public function testGetEmployeesBySubUnit() {
        
        $employees = TestDataService::loadObjectList('Employee', $this->fixture, 'Employee');
        $subUnit = 2;
        $includeTerminatedEmployees = true;

        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
             ->method('getEmployeesBySubUnit')
             ->with($subUnit, $includeTerminatedEmployees)
             ->will($this->returnValue($employees));
        
        $this->employeeService->setEmployeeDao($mockDao);
        $result = $this->employeeService->getEmployeesBySubUnit($subUnit, $includeTerminatedEmployees);
        $this->assertEquals($employees, $result);
    }    
    
    public function testSearchEmployeeList(){
        $employee1 = new Employee();
        $employee1->setLastName('Last Name');
        $employee1->setFirstName('First Name');
        
        $employee2 = new Employee();
        $employee2->setLastName('Last Name');
        $employee2->setFirstName('First Name');
        
        $list   =   array( $employee1,$employee2);
        
        $mockDao = $this->getMock('EmployeeDao');
        $mockDao->expects($this->once())
             ->method('searchEmployeeList')
             ->will($this->returnValue($list));
        
        $this->employeeService->setEmployeeDao($mockDao);
        $result = $this->employeeService->searchEmployeeList();
        $this->assertEquals($list, $result);
        
    }
}
