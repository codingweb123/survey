<?php
namespace MainController;
class MainController
{
    public function index(): void
    {
        view("home", wcompact("config"));
    }
}