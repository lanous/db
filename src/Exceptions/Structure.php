<?php

namespace Lanous\db\Exceptions;

class Structure extends \Exception {
    /**
     * The class you entered as a data table either does not exist or probably has a structural problem.
     */
    const ERR_TABLEND = 900;
    /**
     * You did not use the correct direction, it is better to use predefined constants.
     * <code>\Lanous\db\Lanous::ORDER_DESC</code> or <code>\Lanous\db\Lanous::ORDER_ASC</code>
     */
    const ERR_ORDERDC = 899;
    /**
     * The name of the project you defined in the configuration does not match the namespace of the project files
     */
    const ERR_NMESPCE = 898;
    /**
     * Problem in validation, your data is not acceptable in terms of data type.
     */
    const ERR_VLDDTYP = 897;
    /**
     * The insert structure is not written correctly, please check the documentation.
     */
    const ERR_INSTPTN = 896;
    /**
     * The column you intend to work on is undefined and unknown in the data table class.
     */
    const ERR_CLUMNND = 895;
    /**
     * The data type entered is not defined, please refer to the documentation.
     */
    const ERR_CLASSNF = 894;
    /**
     * You cannot define two or more columns as primary key, primary key is only one key!
     */
    const ERR_MPLEPKY = 893;
    /**
     * The structure of the datatype is incorrect.
     * if this is a custom datatype, please structure it according to the documentation.
     */
    const ERR_DTYPEIC = 892;
    /**
     * The class you have specified is not an ENUM.
     */
    const ERR_NOTENUM = 891;
    /**
     * Interference in parameters
     */
    const ERR_IFEINPR = 890;
    /**
     * Primary key is not set
     */
    const ERR_PKNOTST = 889;
    /**
     * Reference not found
     */
    const ERR_RFRNCNF = 888;

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