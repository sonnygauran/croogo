<?php

App::import('View', 'View', false);
App::import('View', 'Media', false);

class StreamingView extends MediaView {

/**
 * Display or download the given file
 *
 * @return unknown
 */
	function render() {
		$name = $download = $extension = $id = $modified = $path = $size = $cache = $mimeType = null;
		extract($this->viewVars, EXTR_OVERWRITE);

		if ($size) {
			$id = $id . '_' . $size;
		}

		if (is_dir($path)) {
			$path = $path . $id;
		} else {
			$path = APP . $path . $id;
		}

		if (!file_exists($path)) {
			header('Content-Type: text/html');
			$this->cakeError('error404');
		}

		if (is_null($name)) {
			$name = $id;
		}

		if (is_array($mimeType)) {
			$this->mimeType = array_merge($this->mimeType, $mimeType);
		}

		if (isset($extension) && isset($this->mimeType[$extension]) && connection_status() == 0) {
			$chunkSize = 8192;
			$buffer = '';
			$fileSize = @filesize($path); //Content length
			$handle = fopen($path, 'rb');

			if ($handle === false) {
				return false;
			}
			if (!empty($modified)) {
				$modified = gmdate('D, d M Y H:i:s', strtotime($modified, time())) . ' GMT';
			} else {
				$modified = gmdate('D, d M Y H:i:s') . ' GMT';
			}

			if ($download) {
				$contentTypes = array('application/octet-stream');
				$agent = env('HTTP_USER_AGENT');

				if (preg_match('%Opera(/| )([0-9].[0-9]{1,2})%', $agent)) {
					$contentTypes[0] = 'application/octetstream';
				} else if (preg_match('/MSIE ([0-9].[0-9]{1,2})/', $agent)) {
					$contentTypes[0] = 'application/force-download';
					array_merge($contentTypes, array(
						'application/octet-stream',
						'application/download'
					));
				}
				foreach($contentTypes as $contentType) {
					$this->_header('Content-Type: ' . $contentType);
				}
				$this->_header(array(
					'Content-Disposition: attachment; filename="' . $name . '.' . $extension . '";',
					'Expires: 0',
					'Accept-Ranges: bytes',
					'Cache-Control: private' => false,
					'Pragma: private'));

				$httpRange = env('HTTP_RANGE');
				if (isset($httpRange)) {
					list($toss, $range) = explode('=', $httpRange);

					$size = $fileSize - 1;
					$length = $fileSize - $range;

					$this->_header(array(
						'HTTP/1.1 206 Partial Content',
						'Content-Length: ' . $length,
						'Content-Range: bytes ' . $range . $size . '/' . $fileSize));

					fseek($handle, $range);
				} else {
					$this->_header('Content-Length: ' . $fileSize);
				}
			} else {
				$this->_header('Date: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
				if ($cache) {
					if (!is_numeric($cache)) {
						$cache = strtotime($cache) - time();
					}
					$this->_header(array(
						'Cache-Control: max-age=' . $cache,
						'Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache) . ' GMT',
						'Pragma: cache'));
				} else {
					$this->_header(array(
						'Cache-Control: must-revalidate, post-check=0, pre-check=0',
						'Pragma: no-cache'));
				}
                $this->_header(array(
					'Last-Modified: ' . $modified,
					'Content-Type: ' . $this->mimeType[$extension],
//					'Content-Length: ' . $fileSize
                ));
                
                // Consider
                // $size as $fileSize
                $size = $fileSize;
                $length = $fileSize;
                $start = 0;
                $end = $fileSize - 1;
                $this->log('length '.$length);
                header("Accept-Ranges: 0-{$length}");
//                $this->_header(array(
//                    "Accept-Ranges: 0-{$length}"
//                ));

                if (isset($_SERVER['HTTP_RANGE'])) {
                    $this->log('raw range: '.$_SERVER['HTTP_RANGE']);
                    $c_start = $start;
                    $c_end   = $end;
                    // Extract the range string
                    list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
                    // Make sure the client hasn't sent us a multibyte range
                    if (strpos($range, ',') !== false) {

                        // (?) Shoud this be issued here, or should the first
                        // range be used? Or should the header be ignored and
                        // we output the whole content?
                        header('HTTP/1.1 416 Requested Range Not Satisfiable');
                        header("Content-Range: bytes $start-$end/$size");
                        $this->_header(array(
                            'Last-Modified: ' . $modified,
                            'Content-Type: ' . $this->mimeType[$extension],
        //					'Content-Length: ' . $fileSize
                        ));
                        // (?) Echo some info to the client?
                        exit;
                    }
//                    $this->log('what is range?'.  print_r($range, true));
                    // If the range starts with an '-' we start from the beginning
                    // If not, we forward the file pointer
                    // And make sure to get the end byte if spesified
                    if ($range == '-') {

                        // The n-number of the last bytes is requested
                        $c_start = $size - substr($range, 1);
                    }
                    else {

                        $range  = explode('-', $range);
                        $c_start = $range[0];
                        $c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
                    }
                    /* Check the range and make sure it's treated according to the specs.
                    * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
                    */
                    // End bytes can not be larger than $end.
                    $c_end = ($c_end > $end) ? $end : $c_end;
                    // Validate the requested range and return an error if it's not correct.
                    if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {

                        header('HTTP/1.1 416 Requested Range Not Satisfiable');
                        header("Content-Range: bytes $start-$end/$size");
                        // (?) Echo some info to the client?
                        exit;
                    }
                    $start  = $c_start;
                    $end    = $c_end;
                    $length = $end - $start + 1; // Calculate new content length
                    fseek($handle, $start);
                    header('HTTP/1.1 206 Partial Content');
                }
                // Notify the client the byte range we'll be outputting
                $this->_header(array(
					"Content-Range: bytes $start-$end/$size",
					'Content-Type: ' . $this->mimeType[$extension],
                    "Content-Length: $length"
//					'Content-Length: ' . $fileSize
                ));
                
                
                /* cake start*/
			}
			$this->_output();
			$this->_clearBuffer();

			while (!feof($handle)) {
				if (!$this->_isActive()) {
					fclose($handle);
					return false;
				}
				set_time_limit(0);
				$buffer = fread($handle, $chunkSize);
				echo $buffer;
				$this->_flushBuffer();
			}
			fclose($handle);
			return;
		}
		return false;
	}

}
