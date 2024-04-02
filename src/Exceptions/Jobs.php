<?php

namespace Lanous\db\Exceptions;
class Jobs extends \Exception {
    const ERR_RECOVERY = 700;
    const ERR_NOCHANGE = 699;
    const ERR_EXPERROR = 688;
    const ERR_DUPLICTE = 687;
    const ERR_CANTFIND = 686;
}