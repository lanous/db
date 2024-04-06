<?php

namespace Lanous\db\Exceptions;

class NonSupport extends \Exception {
    /**
     * You are using a DBSM that is not defined in the project.
     * check the documentation for the list of supported DBSMs.
     */
    const ERR_DBSM = 800;
    public function __construct(int $code = 0, \Throwable $previous = null) {
        $this->code = $code;
        $constantNames = array_flip(array_filter((new \ReflectionClass(__CLASS__))->getConstants(),static fn($v) => is_scalar($v)));
        $constantNames = $constantNames[$code];
        $PHPDocs = new \ReflectionClassConstant(__CLASS__,$constantNames);
        $PHPDocs = $PHPDocs->getDocComment();
        $PHPDocs = str_replace(["/**","*/","*"],"",$PHPDocs);
        $PHPDocs = trim($PHPDocs);
        $this->message = $PHPDocs." [".__CLASS__."::$constantNames]";
        $trace = $this->getTrace();
        $this->message .= "\n-------------\n";
        array_map(function ($x) {
            if(isset($x['file']) && isset($x['line']))
                    $this->message .= "|- Filename: ".$x["file"]." (l.n:".$x["line"].")\n";
        },$trace);
        $this->message .= "-------------\n";
    }
}