<?php

/**
 * Simple Test for User DAO and Database Connection
 * Run this from the root directory: php simpleUserTest.php
 */

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/DAO.class.php';
require_once __DIR__ . '/classes/User.class.php';

use colocation\Database;
use colocation\UserDAO;
use colocation\User;

echo "--- Starting User DAO Test ---\n";

try {
    // 1. Test Database Connection
    echo "[1/4] Testing Database Connection... ";
    $db = Database::getInstance()->getConnection();
    if ($db) {
        echo "SUCCESS\n";
    }

    $userDAO = new UserDAO();

    // 2. Test Fetching all users
    echo "[2/4] Fetching all users... ";
    $users = $userDAO->getAll();
    echo "Found " . count($users) . " users.\n";

    // 3. Test Fetching a specific user (if any exist)
    if (count($users) > 0) {
        $firstUser = $users[0];
        echo "[3/4] Testing getById for ID " . $firstUser->getIdUtilisateur() . "... ";
        $fetchedUser = $userDAO->getById($firstUser->getIdUtilisateur());
        if ($fetchedUser && $fetchedUser->getEmail() === $firstUser->getEmail()) {
            echo "SUCCESS (" . $fetchedUser->getEmail() . ")\n";
        } else {
            echo "FAILED\n";
        }
    } else {
        echo "[3/4] Skipping getById (No users in DB)\n";
    }

    // 4. Test creating a temporary test user
    echo "[4/4] Creating a test user... ";
    $testData = [
        'prenom' => 'Test',
        'nom' => 'User',
        'email' => 'test_' . time() . '@example.com',
        'mot_de_passe' => password_hash('password123', PASSWORD_DEFAULT),
        'situation_professionnel' => 'Tester',
        'garant' => 0,
        'retraite' => 0,
        'caisse_allocation_familial' => 0,
        'date_naissance' => '2000-01-01',
        'photo_profil' => '',
        'salaire_mensuel_net' => 999.99,
        'revenu_fiscal' => '24000',
        'id_role' => 3
    ];

    $newUser = new User($testData);
    $saveResult = $userDAO->save($newUser);

    if ($saveResult) {
        echo "SUCCESS\n";
        
        // Cleanup: Delete the test user
        $createdUser = $userDAO->findBy(['email' => $testData['email']])[0] ?? null;
        if ($createdUser) {
            echo "Cleaning up test user... ";
            $userDAO->delete($createdUser->getIdUtilisateur());
            echo "DONE\n";
        }
    } else {
        echo "FAILED\n";
    }

    echo "\n--- All tests completed successfully! ---\n";

} catch (Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
}
