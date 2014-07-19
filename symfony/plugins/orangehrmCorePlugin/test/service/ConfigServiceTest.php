<?php



/**
 * ConfigService Test Class
 * @group Core
 */
class ConfigServiceTest extends PHPUnit_Framework_TestCase {

    private $configService;
    
    /**
     * Set up method
     */
    protected function setUp() {
        $this->configService = new ConfigService();        
    }
    
    /**
     * Test the getConfigDao() and setConfigDao() method
     */
    public function testGetSetConfigDao() {
        $dao = $this->configService->getConfigDao();
        $this->assertTrue($dao instanceof ConfigDao);
        
        $mockDao = $this->getMock('ConfigDao');
        $this->configService->setConfigDao($mockDao);
        $dao = $this->configService->getConfigDao();
        $this->assertEquals($dao, $mockDao);
    }

    /**
     * Test the setIsLeavePeriodDefined() method
     */
    public function testSetIsLeavePeriodDefined() {
        
        $value = 'Yes';
        
        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('setValue')
                 ->with(ConfigService::KEY_LEAVE_PERIOD_DEFINED, $value);

        $this->configService->setConfigDao($mockDao);

        $this->configService->setIsLeavePeriodDefined($value);
        
        // with invalid parameters        
        try {
            $this->configService->setIsLeavePeriodDefined('test');
            $this->fail("Exception expected when invalid value passed to setisLeavePeriodDefined()");
        } catch (Exception $e) {
            // expected
        }
    }

    /**
     * Test isLeavePeriodDefined()
     */
    public function testIsLeavePeriodDefined() {
        $value = true;
        
        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('getValue')
                 ->with(ConfigService::KEY_LEAVE_PERIOD_DEFINED)
                 ->will($this->returnValue($value));

        $this->configService->setConfigDao($mockDao);

        $returnVal = $this->configService->isLeavePeriodDefined();
        $this->assertEquals($value, $returnVal);        
    }

    /**
     * Test setShowPimDeprecatedFields() method
     */
    public function testSetShowPimDeprecatedFields() {
        
        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('setValue')
                 ->with(ConfigService::KEY_PIM_SHOW_DEPRECATED, 1);

        $this->configService->setConfigDao($mockDao);

        $this->configService->setShowPimDeprecatedFields(true);
        
        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('setValue')
                 ->with(ConfigService::KEY_PIM_SHOW_DEPRECATED, 0);

        $this->configService->setConfigDao($mockDao);

        $this->configService->setShowPimDeprecatedFields(false);
        
    }
    
    /**
     * Test showPimDeprecatedFields() method
     */
    public function testShowPimDeprecatedFields() {
        
        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('getValue')
                 ->with(ConfigService::KEY_PIM_SHOW_DEPRECATED)
                 ->will($this->returnValue('1'));

        $this->configService->setConfigDao($mockDao);

        $returnVal = $this->configService->showPimDeprecatedFields();
        $this->assertTrue($returnVal);

        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('getValue')
                 ->with(ConfigService::KEY_PIM_SHOW_DEPRECATED)
                 ->will($this->returnValue('0'));

        $this->configService->setConfigDao($mockDao);

        $returnVal = $this->configService->showPimDeprecatedFields();
        $this->assertFalse($returnVal);
        
    }

    public function testSetShowPimSSN() {

        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('setValue')
                 ->with(ConfigService::KEY_PIM_SHOW_SSN, 1);

        $this->configService->setConfigDao($mockDao);

        $this->configService->setShowPimSSN(true);
        
        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('setValue')
                 ->with(ConfigService::KEY_PIM_SHOW_SSN, 0);

        $this->configService->setConfigDao($mockDao);

        $this->configService->setShowPimSSN(false);        
    }

    public function testShowPimSSN() {

        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('getValue')
                 ->with(ConfigService::KEY_PIM_SHOW_SSN)
                 ->will($this->returnValue('1'));

        $this->configService->setConfigDao($mockDao);

        $returnVal = $this->configService->showPimSSN();
        $this->assertTrue($returnVal);

        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('getValue')
                 ->with(ConfigService::KEY_PIM_SHOW_SSN)
                 ->will($this->returnValue('0'));

        $this->configService->setConfigDao($mockDao);

        $returnVal = $this->configService->showPimSSN();
        $this->assertFalse($returnVal);        
    }

    public function testSetShowPimSIN() {

        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('setValue')
                 ->with(ConfigService::KEY_PIM_SHOW_SIN, 1);

        $this->configService->setConfigDao($mockDao);

        $this->configService->setShowPimSIN(true);
        
        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('setValue')
                 ->with(ConfigService::KEY_PIM_SHOW_SIN, 0);

        $this->configService->setConfigDao($mockDao);

        $this->configService->setShowPimSIN(false);          
    }

    public function testShowPimSIN() {

        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('getValue')
                 ->with(ConfigService::KEY_PIM_SHOW_SIN)
                 ->will($this->returnValue('1'));

        $this->configService->setConfigDao($mockDao);

        $returnVal = $this->configService->showPimSIN();
        $this->assertTrue($returnVal);

        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('getValue')
                 ->with(ConfigService::KEY_PIM_SHOW_SIN)
                 ->will($this->returnValue('0'));

        $this->configService->setConfigDao($mockDao);

        $returnVal = $this->configService->showPimSIN();
        $this->assertFalse($returnVal);         
    }

    public function testSetShowPimTaxExemptions() {

        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('setValue')
                 ->with(ConfigService::KEY_PIM_SHOW_TAX_EXEMPTIONS, 1);

        $this->configService->setConfigDao($mockDao);

        $this->configService->setShowPimTaxExemptions(true);
        
        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('setValue')
                 ->with(ConfigService::KEY_PIM_SHOW_TAX_EXEMPTIONS, 0);

        $this->configService->setConfigDao($mockDao);

        $this->configService->setShowPimTaxExemptions(false);      
        
        // Exception
        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('setValue')
                 ->with(ConfigService::KEY_PIM_SHOW_TAX_EXEMPTIONS, 0)
                 ->will($this->throwException(new DaoException()));                
        
        $this->configService->setConfigDao($mockDao);
        
        try {
            $this->configService->setShowPimTaxExemptions(false);      
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof CoreServiceException);
        }
        
    }

    public function testShowPimTaxExemptions() {

        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('getValue')
                 ->with(ConfigService::KEY_PIM_SHOW_TAX_EXEMPTIONS)
                 ->will($this->returnValue('1'));

        $this->configService->setConfigDao($mockDao);

        $returnVal = $this->configService->showPimTaxExemptions();
        $this->assertTrue($returnVal);

        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('getValue')
                 ->with(ConfigService::KEY_PIM_SHOW_TAX_EXEMPTIONS)
                 ->will($this->returnValue('0'));

        $this->configService->setConfigDao($mockDao);
        
        $returnVal = $this->configService->showPimTaxExemptions();
        $this->assertFalse($returnVal);         
        
        // Exception
        $mockDao = $this->getMock('ConfigDao');
        $mockDao->expects($this->once())
                 ->method('getValue')
                 ->with(ConfigService::KEY_PIM_SHOW_TAX_EXEMPTIONS)
                 ->will($this->throwException(new DaoException()));
        
        $this->configService->setConfigDao($mockDao);
        
        try {
            $returnVal = $this->configService->showPimTaxExemptions();
            $this->fail("Exception expected");
        } catch (Exception $e) {
            $this->assertTrue($e instanceof CoreServiceException);
        }

        
    }
    
}

?>