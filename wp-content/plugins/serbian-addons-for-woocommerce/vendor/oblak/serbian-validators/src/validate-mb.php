<?php

namespace Oblak;

function validateMB($mb) {
    return strlen($mb) == 8 && substr($mb, -1) == mod11(substr($mb, 0, 7));
}
