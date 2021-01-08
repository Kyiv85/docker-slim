<?php

namespace Tests;

final class BandTest extends \PHPUnit\Framework\TestCase {

    protected function setUp(): void
    {        
	   $this->band = new \Aivo\Band;
    }
    
    public function testGetToken() 
    {
        $token = $this->band->authorize();
        $this->assertIsString($token);	
    }

    public function testBandFound() 
    {
        $id = $this->band->getBandID('Nirvana');
        $this->assertIsString($id);	
    }

    public function testBandNotFound() 
    {
        $err = $this->band->getBandID('BandaQueNoSeVaAConseguir');
        $this->assertEquals($err['status'], 'Error');
        $this->assertEquals($err['mensaje'], 'No se encontraron artistas con ese nombre');
    }

    public function testFormatResponse()
    {
        $information = $this->band->getInformation('Nirvana');
        $this->assertIsArray($information);
        foreach($information as $i){
            if(strpos($i['name'], 'Nirvana')){
                $this->assertStringContainsString($i['name'], 'Nirvana');
            }
            if(strpos($i['name'], 'MTV')){
                $this->assertStringContainsString($i['name'], 'MTV');
            }
            if(strpos($i['name'], 'Nevermind')){
                $this->assertStringContainsString($i['name'], 'Nevermind');
            }
        }
    }
}