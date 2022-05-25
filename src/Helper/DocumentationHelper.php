<?php

namespace App\Helper;

/**
 * Class DocumentationHelper
 * @package App\Helper
 */
class DocumentationHelper
{
    /**
     * @param array|string[] $path
     * @return array|null
     */
    public static function getFiles(array $path = null): ?array
    {
        // Create the path of the folder that has to be opened
        $newPath = array('..', 'src', 'wase-physics-docs');
        if ($path) {
            foreach ($path as $item) {
                array_push($newPath, $item);
            }
        }

        // Get all the files in the documentation folder
        $files = scandir(realpath(join(DIRECTORY_SEPARATOR, $newPath)));
        $links = [];

        // Loop through all the files
        foreach ($files as $file) {
            // If it doesn't start with a number continue
            if (!self::startsWithNumber($file))
                continue;

            if (self::isFolder($file)) {
                $links[self::createTitle($file)] = self::getFiles(array($file));
            } else {
                $links[self::createTitle($file)] = $file;
            }
        }

        return $links;
    }


    /**
     * @param array|null $path
     * @return array|null
     */
    public static function getFilesInFolder(array $path = null): ?array
    {
        // Create the path of the folder that has to be opened
        $newPath = array('..', 'src', 'wase-physics-docs');
        if ($path) {
            foreach ($path as $item) {
                array_push($newPath, $item);
            }
        }

        // Get all the files in the folder
        $files = scandir(realpath(join(DIRECTORY_SEPARATOR, $newPath)));
        $links = [];

        // Loop through all the files
        foreach ($files as $file) {
            // If it doesn't start with a number continue
            if (!self::startsWithNumber($file))
                continue;

            $links[self::createTitle($file)] = $file;
        }

        return $links;
    }

    /**
     * @param array $filePath
     * @return string|null
     */
    public static function getContent(array $filePath): ?string
    {
        try {
            // Create the path of the file that has to be opened
            $path = array('..', 'src', 'wase-physics-docs');
            foreach ($filePath as $item) {
                array_push($path, $item);
            }

            $filePath = realpath(join(DIRECTORY_SEPARATOR, $path));

            // Open the file
            $file = fopen($filePath, 'r');

            // Read and store the content in the file
            $fileContent = fread($file, filesize($filePath));

            // Close the file
            fclose($file);

            return $fileContent;
        } catch (\Throwable $exception) {
            return null;
        }
    }

    /**
     * @param string $slug
     * @param array $files
     * @return string|null
     */
    public static function fileName(string $slug, array $files): ?string
    {
        // Replace every - with an _
        $slug = str_replace('-', '_', $slug);

        // Search if there is a file that contains the name in the $files array
        foreach ($files as $file) {
            // If there is a file that contains the slug
            if (strpos($file, $slug) != false) {
                // Check if the file title name doesn't contain more except for the slug part
                if (strlen(self::createTitle($file)) - strlen($slug) != 0)
                    continue;

                // If there is a file that contains the name return it.
                return $file;
            }
        }

        return null;
    }

    /**
     * @param string $str
     * @return string
     */
    private static function createTitle(string $str): string
    {
        // Split the title at every underscore
        $array = explode('_', $str);
        $name = null;

        // Remove the number prefix
        for ($i = 1; $i < sizeof($array); $i++)
            $name .= $array[$i] . ' ';

        // If it is a markdown file remove the extension, if it is a folder remove the space at the end.
        if (substr($name, -4) == '.md ') {
            $name = substr($name, 0, -4);
        } else {
            $name = substr($name, 0, -1);
        }

        return $name;
    }

    /**
     * @param string $str
     * @return bool
     */
    private static function isFolder(string $str): bool
    {
        return substr($str, -3) != '.md';
    }

    /**
     * @param string $str
     * @return bool
     */
    private static function startsWithNumber(string $str): bool
    {
        return preg_match('/^\d/', $str) === 1;
    }
}