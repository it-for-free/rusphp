#!/usr/bin/env php
<?php
/**
 * @todo не хватат комментариев, возможно улучшения структуры
 */
$options = [
    'ext'     => 'php',
    'dir'     => '.',
    'exclude' => '',
    'out'     => '',
];
 
$options = parseArgs($options, $argv);
 
$extensions = getMultiArg($options['ext']);
$regex = '/(' . implode('|', array_map(function($v) {return '\.' . $v;}, $extensions)) . ')$/i';
 
$pwd = getcwd();
 
$directory = new RecursiveDirectoryIterator($pwd, FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS);
$iterator  = new RecursiveIteratorIterator($directory);
$regex     = new RegexIterator($iterator, $regex, RecursiveRegexIterator::GET_MATCH);
 
$fout = $options['out'] ? fopen($options['out'], 'w') : STDOUT;
if (!$fout) {
    fwrite(STDERR, 'Can\'t open file ' . $options['out'] . PHP_EOL);
    exit;
}
 
fwrite($fout, <<<EOL
<style type="text/css">
.heading {
    font-weight: bold;
}
.source {
    white-space: pre;
}
</style>
EOL
);
 
$excludes = getMultiArg($options['exclude']);
foreach ($regex as $file => $val) {
    $relative = substr($file, strlen($pwd) + 1);
    $byPass = false;
    foreach ($excludes as $exclude) {
 
        if (0 == strncasecmp($relative, $exclude, strlen($exclude))) {
            $byPass = true;
            break;
        }
    }
    if ($byPass) {
        continue;
    }
 
    $source = str_replace(' ', ' ', htmlspecialchars(file_get_contents($file)));
    fwrite($fout, '<h3 class="heading">' . $relative . '</h3>' . "\n");
    fwrite($fout, '<pre class="source">' . "\n");
    fwrite($fout, $source);
    fwrite($fout, '</pre>' . "\n");
}
 
fclose($fout);
 
function parseArgs($options, $argv)
{
    array_shift($argv);
    foreach ($argv as $item) {
        if (preg_match('/(?<key>[a-z]+)=(?<value>[^\ ]*)/i', $item, $matches)) {
            if (isset($options[$matches['key']])) {
                $options[$matches['key']] = $matches['value'];
            } else {
                fwrite(STDERR, 'Unknown option ' . $matches['key'] . PHP_EOL) ;
                exit;
            }
        }
    }
 
    return $options;
}
 
function getMultiArg($val)
{
    return array_values(array_filter(preg_split('/[, ;]/', $val)));
}

