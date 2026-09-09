<?php
$files = glob('database/migrations/*.php');
foreach ($files as $file) {
    $content = file_get_contents($file);
    if (preg_match('/Schema::create\(\s*\'([^\']+)\'/', $content, $matches)) {
        $table = $matches[1];
        if (strpos($content, "if (!Schema::hasTable('$table'))") === false) {
            $content = str_replace(
                "Schema::create('$table'",
                "if (!Schema::hasTable('$table')) {\n            Schema::create('$table'",
                $content
            );
            $content = preg_replace(
                "/(Schema::create\('$table'.*?}\);)/s",
                "$1\n        }",
                $content
            );
            file_put_contents($file, $content);
        }
    }
}
