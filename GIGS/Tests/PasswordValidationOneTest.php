<?php
declare(strict_types=1);
require '../Public/reg_account.php';
use PHPUnit\Framework\TestCase;


final class PasswordValidationOneTest extends TestCase {

    public function testValidPassword() {
        $userPWD = 'SecureP@ssword1';
        $userPWD2 = 'SecureP@ssword1';
        $validation = checkPassword($userPWD, $userPWD2);
        $this->assertFalse($validation->notMatch);
        $this->assertFalse($validation->tooShort);
    }

    public function testPasswordsNotMatch() {
        $userPWD = 'Password123';
        $userPWD2 = 'MismatchedPassword';
        $validation = checkPassword($userPWD, $userPWD2);
        $this->assertTrue($validation->notMatch);
        $this->assertFalse($validation->tooShort);
    }

    public function testShortPassword() {
        $userPWD = 'Short1';
        $userPWD2 = 'Short1';
        $validation = checkPassword($userPWD, $userPWD2);
        $this->assertFalse($validation->notMatch);
        $this->assertTrue($validation->tooShort);
    }
    public function testShortNotMatchPassword() {
        $userPWD = 'Short1';
        $userPWD2 = 'Short2';
        $validation = checkPassword($userPWD, $userPWD2);
        $this->assertTrue($validation->notMatch);
        $this->assertTrue($validation->tooShort);
    }

}