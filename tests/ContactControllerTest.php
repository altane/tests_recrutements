<?php

namespace Tests;

use App\Controllers\ContactController;
use PHPUnit\Framework\TestCase;

define('ROOT', dirname(dirname(__DIR__ . '/..')));

class ContactControllerTest extends TestCase
{
    public function testMethodeIndex()
    {
        $_SESSION["auth"]["id"] = 1;
        $controller = $this->getMockBuilder(ContactController::class)
            ->setMethods(["index"])
            ->getMock();

        $controller->expects($this->never())
            ->method("index")
            ->willThrowException(\Exception::class);
    }

    public function testMethodeAdd()
    {
        $controller = $this->getMockBuilder(ContactController::class)
            ->setMethods(["add"])
            ->getMock();

        $controller->expects($this->never())
            ->method("add")
            ->willThrowException(\Exception::class);
    }

    public function testMethodeEdit()
    {
        $controller = $this->getMockBuilder(ContactController::class)
            ->setMethods(["edit"])
            ->getMock();

        $controller->expects($this->never())
            ->method("edit")
            ->willThrowException(\Exception::class);
    }

    public function testMethodeDelete()
    {
        $controller = $this->getMockBuilder(ContactController::class)
            ->setMethods(["delete"])
            ->getMock();

        $controller->expects($this->never())
            ->method("delete")
            ->willThrowException(\Exception::class);
    }

    public function testMethodeSanitize()
    {
        $controller = $this->getMockBuilder(ContactController::class)
            ->setMethods(["sanitize"])
            ->getMock();

        $controller->expects($this->any())
            ->method("sanitize")
            ->with(["name" => "Moom", "email" => "test@test"])
            ->willThrowException(\Exception::class);
    }
}