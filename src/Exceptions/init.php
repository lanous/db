<?php

namespace Lanous\db\Exceptions;

class init extends \Exception {
    /**
     * The database connection encountered an issue;
     * the configuration information (including host, username, password, and database name) is incorrect!
     */
    const ERR_CNCTERR = 600;

    public function __construct(int $code = 0, \Throwable $previous = null) {
        $constantNames = array_flip(array_filter((new \ReflectionClass(__CLASS__))->getConstants(),static fn($v) => is_scalar($v)));
        $constantNames = $constantNames[$code];
        $PHPDocs = new \ReflectionClassConstant(__CLASS__,$constantNames);
        $PHPDocs = $PHPDocs->getDocComment();
        $PHPDocs = str_replace(["/**","*/","*"],"",$PHPDocs);
        $PHPDocs = trim($PHPDocs);
        $this->message = $PHPDocs;
        $trace = $this->getTrace();
        $this->message .= "\n-------------\n";
        array_map(function ($x) {
            $this->message .= "|- Filename: ".$x["file"]." (l.n:".$x["line"].")\n";
        },$trace);
        $this->message .= "-------------\n";
    }
}