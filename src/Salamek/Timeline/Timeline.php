<?php

namespace Salamek\Timeline;


/**
 * Class TemplatedEmail
 * @package Salamek\TemplatedEmail
 */
class Timeline extends Nette\Object
{
    private $tempPath;

    private $presenterNamespace;


    public function __construct($tempPath, $presenterNamespace)
    {
        $this->setTempPath($tempPath);
        $this->setPresenterNamespace($presenterNamespace);
    }


    public function setTempPath($tempPath)
    {
        $this->tempPath = $tempPath;
    }


    public function setPresenterNamespace($presenterNamespace)
    {
        $this->presenterNamespace = $presenterNamespace;
    }

}
