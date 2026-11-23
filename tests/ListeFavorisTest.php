<?php

namespace App\Tests;

use App\Models\ListeFavoris;
use App\Models\ListeFavorisDAO;
use PHPUnit\Framework\TestCase;

class ListeFavorisTest extends TestCase {
    public function testNewListReceivesCreationDate(): void {
        $before = date('Y-m-d');
        foreach ([[], ['date_creation' => null], ['date_creation' => '']] as $data) {
            $liste = new ListeFavoris($data);
            $this->assertContains($liste->getDateCreation(), [$before, date('Y-m-d')]);
        }
    }

    public function testStoredCreationDateIsPreserved(): void {
        $liste = new ListeFavoris(['date_creation' => '2025-06-15']);
        $this->assertSame('2025-06-15', $liste->getDateCreation());
    }

    public function testCreationDateCanBeUpdated(): void {
        $liste = new ListeFavoris([]);
        $this->assertSame($liste, $liste->setDateCreation('2025-07-20'));
        $this->assertSame('2025-07-20', $liste->getDateCreation());
    }

    public function testSaveIncludesCreationDate(): void {
        $liste = new ListeFavoris(['titre_liste' => 'Mes favoris', 'id_utilisateur' => 11]);
        $db = $this->createMock(\PDO::class);
        $statement = $this->createMock(\PDOStatement::class);
        $db->expects($this->once())->method('prepare')
            ->with($this->callback(fn(string $sql): bool => str_contains($sql, ':date_creation')))
            ->willReturn($statement);
        $statement->expects($this->once())->method('execute')->with($this->callback(
            fn(array $data): bool => $data['date_creation'] === $liste->getDateCreation()
                && $data['titre_liste'] === 'Mes favoris' && $data['id_utilisateur'] === 11
        ))->willReturn(true);

        $reflection = new \ReflectionClass(ListeFavorisDAO::class);
        $dao = $reflection->newInstanceWithoutConstructor();
        foreach (['db' => $db, 'table_name' => 'liste_favoris', 'primary_key' => 'id_listeFavoris'] as $name => $value) {
            $reflection->getProperty($name)->setValue($dao, $value);
        }
        $this->assertTrue($dao->save($liste));
    }
}
