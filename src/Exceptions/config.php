<?php

namespace Lanous\db\Exceptions;

class Config extends \Exception {
    /**
     * The configuration structure has not been written correctly;
     * please review the documentation carefully
     */
    const ERR_CGCLSIC = 500;
    public function __construct(int $code = 0, \Throwable $previous = null) {
        $constantNames = array_flip(array_filter((new \ReflectionClass(__CLASS__))->getConstants(),static fn($v) => is_scalar($v)));
        $constantNames = $constantNames[$code];
        $PHPDocs = new \ReflectionClassConstant(__CLASS__,$constantNames);
        $PHPDocs = $PHPDocs->getDocComment();
        $PHPDocs = str_replace(["/**","*/","*"],"",$PHPDocs);
        $PHPDocs = trim($PHPDocs);
        $this->message = $PHPDocs;
        $trace = $this->getTrace();
        unset($trace[0]);
        if(count($trace) > 0) {
            $this->message .= "\n-------------\n";
            array_map(function ($x) {
                if(isset($x['file']) && isset($x['line']))
                    $this->message .= "|- Filename: ".$x["file"]." (l.n:".$x["line"].")\n";
            },$trace);
            $this->message .= "-------------\n";
        }
    }
}