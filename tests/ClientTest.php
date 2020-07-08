<?php

namespace Airtable\Tests;

use AirTable\Exceptions\AirTableApiException;
use PHPUnit\Framework\TestCase;
use AirTable\AirTable;

class ClientTest extends TestCase
{
   public function testClient() {
       if (!file_exists(__DIR__ . '/AirTableTestCredentials.php')) {
           throw new AirTableApiException(
               'You must create a AirTableTestCredentials.php file from AirTableTestCredentials.php.dist'
           );
       } else {
           require_once "AirTableTestCredentials.php";
       }

       if (!strlen(AirTableTestCredentials::$token) || !strlen(AirTableTestCredentials::$base) || !strlen(AirTableTestCredentials::$table) || !strlen(AirTableTestCredentials::$testColumn)) {
           throw new AirTableApiException(
               'You must fill out AirTableTestCredentials.php'
           );
       }

       $client = new AirTable(["token" => AirTableTestCredentials::$token, "base" => AirTableTestCredentials::$base]);

       $records = $client->table(AirTableTestCredentials::$table)->list();

       foreach ($records as $record) {
           $deleted = $client->table(AirTableTestCredentials::$table)->delete($record->getId());

           $this->assertTrue($deleted->isDeleted());
       }

       $newRow = $client->table(AirTableTestCredentials::$table)->create([AirTableTestCredentials::$testColumn => AirTableTestCredentials::$testColumn]);

       $this->assertEquals($newRow->{AirTableTestCredentials::$testColumn }, AirTableTestCredentials::$testColumn);

       $records = $client->table(AirTableTestCredentials::$table)
           ->fields([AirTableTestCredentials::$testColumn])
           ->sort(AirTableTestCredentials::$testColumn, "desc")
           ->filterByFormula(AirTableTestCredentials::$testColumn, "=", AirTableTestCredentials::$testColumn)
           ->pageSize(15)
           ->maxRecords(100)
           ->list()
       ;

       $this->assertNotEmpty($records);

       foreach ($records as $record) {
           $this->assertEquals($record->{AirTableTestCredentials::$testColumn}, AirTableTestCredentials::$testColumn);

           $updatedRecord = $record->update([AirTableTestCredentials::$testColumn => "TEST"]);

           $this->assertEquals($updatedRecord->{AirTableTestCredentials::$testColumn}, "TEST");

           $deletedRecord = $client->table(AirTableTestCredentials::$table)->delete($record->getId());

           $this->assertTrue($deletedRecord->isDeleted());
       }
   }
}