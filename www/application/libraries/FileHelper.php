<?php

class FileHelper
{
	function FileHelper()
	{
		// All methods are static.
	}

	/**
	* This function will return the contents of the source file (as a string) if the
	* cached path has expired, otherwise the contents of the file at the cached path are
	* returned. A file has expired if the number of seconds in $lifeSpan has occured since
	* the cached file was last saved.
	*
	* If the cache has expired and a new file is retrieved from the source path,
	* its contents are saved to the cache path as a string.
	*
	* If $lifeSpan is null or 0, all cache functions are bypassed. No cache files
	* are read or written. The contents of $sourcePath are immediately read.
	*
	* @param string $sourcePath
	* @param string $cachedPath
	* @param long $lifeSpan
	*/
	public static function getCachedFile($sourcePath, $cachedPath, $lifeSpan = 0)
	{
		$handle = null;
		$contents = null;

		if ($lifeSpan == 0)
		{
			return file_get_contents($sourcePath);
		}

		try
		{
			if (!file_exists($cachedPath)
				|| (filemtime($cachedPath) + $lifeSpan) < time())
			{
				/*
					Either cashed file doesn't exists or it has expired, so
					look up the source file and save to the cache.
				*/
				$contents = file_get_contents($sourcePath);

				// Write file.
				$handle = fopen($cachedPath, 'w+') or die("Can't open file: " . $cachedPath);
				fwrite($handle, $contents);
				fclose($handle);
			}
			else
			{
				/*
					Get contents from file cache.
				*/
				$contents = file_get_contents($cachedPath);
			}

			return $contents;
		}
		catch (Exception $ex)
		{
			if ($handle != null)
			{
				fclose($handle);
				$handle = null;
			}
		}
	}

	public static function readFromFile($filename)
	{
		$ret = "";

		try
		{
			if (filesize($filename) > 0)
			{
				$fh = fopen($filename, 'r') or die("can't open file");
				$ret = fread($fh, filesize($filename));
				fclose($fh);
			}

			return $ret;
		}
		catch (Exception $ex)
		{
			if ($fh != null)
			{
				fclose($fh);
				$fh = null;
			}

			// Log error.
			LogglyHelper::logError("Error reading from file: " . $ex->getMessage());
		}
	}

	public static function downloadFile($url, $destFilename)
	{
		$source = null;
		$destination = null;

		try
		{
			$destination = fopen($destFilename, "w");
			$source = fopen($url, "r");
			while ($a = fread($source, 1024)) fwrite($destination, $a);
			fclose($source);
			fclose($destination);
			return true;
		}
		catch (Exception $ex)
		{
			if ($source != null)
			{
				fclose($source);
				$source = null;
			}

			if ($destination != null)
			{
				fclose($destination);
				$destination = null;
			}

			// Log error.
			LogglyHelper::logError("Error downloading file: " . $ex->getMessage());
			return false;
		}
	}

	public static function writeToFile($filename, $content, $deleteFirst = true)
	{
		try
		{
			// Delete file if it exists?
			if (toBoolean($deleteFirst) === true)
			{
				FileHelper::deleteFile($filename);
			}

			$fh = fopen($filename, 'w') or die("can't open file");
			fwrite($fh, $content);
			fclose($fh);
		}
		catch (Exception $ex)
		{
			if ($fh != null)
			{
				fclose($fh);
				$fh = null;
			}

			// Log error.
			LogglyHelper::logError("Error writing to file: " . $ex->getMessage());
		}
	}

	public static function deleteFile($filename)
	{
		try
		{
			if (file_exists($filename))
			{
				unlink($filename);
			}
		}
		catch (Exception $ex)
		{
			// Log error.
			LogglyHelper::logError("Could not delete file: " . $filename);
		}
	}
    
    public static function generateUniqueFilePath($basePath)
    {
        $basePath = trim($basePath);

        if (!strlen($basePath))
            throw new Exception('BasePath is required!');
        
        if (strcmp(substr($basePath, -1), DIRECTORY_SEPARATOR) != 0)
            throw new Exception("BasePath must end with directory separator: " . DIRECTORY_SEPARATOR);
        
        return $basePath .= uniqid() . DIRECTORY_SEPARATOR;
    }
    
    public static function createUniqueFilePath($basePath)
    {
        $path = FileHelper::generateUniqueFilePath($basePath);

        if (!mkdir($path))
            throw new Exception("Unable to create file path: " . $path);

        return $path;
    }
}

?>