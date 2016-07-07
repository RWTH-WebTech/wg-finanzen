<?php
namespace WGFinanzen\Page;

interface PageInterface{

    public function getTitle();

    public function getViewScript();

    public function getViewVariables();

}