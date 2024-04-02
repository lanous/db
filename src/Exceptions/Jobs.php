<?php

namespace Lanous\db\Exceptions;
class Jobs extends \Exception {
    /**
     * A critical and dangerous issue for data;
     * when data recovery operations encounter problems in a [JOB], you receive this error.
     */
    const ERR_RECOVERY = 700;
    /**
     * The data editing operation has been done without problems,
     * but there is no difference with the previous data!
     */
    const ERR_NOCHANGE = 699;
    /**
     * An error was encountered while editing data.
     */
    const ERR_EXPERROR = 688;
    /**
     * Probably you are extracting a duplicate data with two variables.
     * this is outside the structural rules.
     */
    const ERR_DUPLICTE = 687;
    /**
     * The row you are looking for does not exist.
     */
    const ERR_CANTFIND = 686;
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