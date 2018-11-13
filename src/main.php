<?php

use function SasaB\ReactBridge\str_has;
use function SasaB\ReactBridge\js_code;
use function SasaB\ReactBridge\html;
use function SasaB\ReactBridge\is_prod;
use function SasaB\ReactBridge\env;
use const SasaB\ReactBridge\BOOT_CODE;

/**
 * @param string $id
 * @param array $data
 * @param array $options
 * @return string
 */
function react_component(string $id, array $data = [], array $options = []): string
{
    if (is_prod()) return html($id, $data);

    $opts = array_merge([
        'ref' => false,
        'component' => null,
        'path' => env('REACT_BRIDGE_PATH', dirname(__DIR__, 2).'/resources/assets/js'),
        'folder' => env('REACT_BRIDGE_FOLDER', 'components'),
        'filename' => env('REACT_BRIDGE_FILENAME', 'react')
    ], $options);

    $writeTo = $opts['path']."/".$opts['filename'].".js";

    if (file_exists($writeTo)) {
        $file = file_get_contents($writeTo);

        [$comment, $import, $code] = js_code($id, $opts['component'], $opts['ref'], $opts['folder']);

        if (!str_has($file, $id) && !str_has($file, $code)) {
            $write = str_has($file, $import) ? "{$comment}{$code}" : "{$comment}{$import}{$code}";
            file_put_contents($writeTo, $write, FILE_APPEND);
        }

        return html($id, $data);
    }

    file_put_contents($writeTo, BOOT_CODE.implode(js_code($id, $opts['component'], $opts['ref'], $opts['folder'])));

    return html($id, $data);
}

/**
 * @alias react_component
 * @param string $id
 * @param array $data
 * @param array $options
 * @return string
 */
function rc(string $id, array $data = [], array $options = []): string
{
    return react_component($id, $data, $options);
}
