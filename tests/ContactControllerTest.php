<?php

namespace Tests;

use App\Controllers\ContactController;
use PHPUnit\Framework\TestCase;

class ContactControllerTest extends TestCase
{
    public function methodeIndexTest()
    {
        $controller = $this->getMockBuilder(ContactController::class)
            ->setMethods(["index"])
            ->getMock();

        $controller->expects($this->never())
            ->method("index")
            ->willThrowException(\Exception::class);
    }

    public function TestMethodeAdd()
    {
        $controller = $this->getMockBuilder(ContactController::class)
            ->setMethods(["add"])
            ->getMock();

        $controller->expects($this->never())
            ->method("add")
            ->willThrowException(\Exception::class);
    }

    public function TestMethodeEdit()
    {
        $controller = $this->getMockBuilder(ContactController::class)
            ->setMethods(["edit"])
            ->getMock();

        $controller->expects($this->never())
            ->method("edit")
            ->willThrowException(\Exception::class);
    }

    public function TestMethodeDelete()
    {
        $controller = $this->getMockBuilder(ContactController::class)
            ->setMethods(["delete"])
            ->getMock();

        $controller->expects($this->never())
            ->method("delete")
            ->willThrowException(\Exception::class);
    }

    public function TestMethodeSanitize()
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