<?php



function dumper($e) {
    print_r($e);
}

function splat($e) {
        dumper($e);
        die();
}
