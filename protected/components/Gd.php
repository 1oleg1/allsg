<?php
class Image_Gd extends CApplicationComponent {

    /**
     * Функция получения названия файла для вставки его в атрибут src html-тега img
     *
     * @param string $srcFile - исходный файл
     * @param int $newWidth - новая ширина
     * @param int $newHeight - новая высота
     * @param string $label - фаил с шильдиком
     * @return string
     */
    public function getImage($srcFile, $newWidth, $newHeight, $label=false)
    {
        $srcFileFull = $_SERVER['DOCUMENT_ROOT'] . $srcFile;

        if (is_file($srcFileFull)) {
        
            $srcImgInfo = getimagesize($srcFileFull);
            //image_type_to_extension( $srcImgInfo[2] );
            //if ($srcImgInfo[2] == 1)     $ext = '.gif'; 
            //elseif ($srcImgInfo[2] == 2) $ext = '.jpg';  
            //elseif ($srcImgInfo[2] == 3) $ext = '.png';  
                                
            $fileInfo = pathinfo($srcFile);
            $fileName = $fileInfo['filename'];

            $targetFile = $fileName . 'w' . $newWidth . 'h' . $newHeight;
            $targetFile = $fileInfo['dirname'] . '/cache/' . md5($targetFile).image_type_to_extension($srcImgInfo[2]);
            $targetFileFull = $_SERVER['DOCUMENT_ROOT'] . $targetFile;

            if (!is_file($targetFileFull)) {
                $result = $this->resizeImagick($srcFileFull, $targetFileFull, $newWidth);
            }

            return $targetFile;

        } else {
            return 'файл не найден';
        }
    }

/**
 * Функция создания квадратного изображения с кадрированием.
 * @param string $sourceFile - путь до исходного файла
 * @param string $destinationFile - путь файла, в который сохраняется результат
 * @param integer $biggestSideSize - размер стороны квадратного изображения
 * @param boolean $makeSquare - пока бесполезно, скорее задел на будущее, чтобы 1 функцией работать с Imagick
 * @return 
 */
    public function resizeImagick( $sourceFile, $destinationFile, $biggestSideSize = 120, $makeSquare = true ){
	$info = getimagesize( $sourceFile );

	//$destinationFile = $destinationFile . image_type_to_extension( $info[2] );

	# проверяем на формат входного файла
	if ( false === in_array( $info[2], array( IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_PNG ) ) )
		return false;

	$originalWidth = $info[0];
	$originalHeight = $info[1];

	# создаём новый пустой объект
	$newFileObj = new Imagick();
	# оригинальное изображение
	$im = new Imagick( $sourceFile );

	if ( true === $makeSquare ){
		if ( $originalWidth < $originalHeight ) {
			$newWidth = $biggestSideSize;
			$newHeight = 0;
		} else {
			$newWidth = 0;
			$newHeight = $biggestSideSize;
		}

		$original_ratio = $originalWidth / $originalHeight;

		if ($original_ratio >= 1) {
			# ширина уменьшенной картинки (высота равна $biggestSideSize )
			$src_w = ( $originalWidth * $biggestSideSize ) / $originalHeight;
			$src_x = ( $src_w - $biggestSideSize) / 2;
			$src_y = 0;
		}
		else {
			$src_h = ( $originalHeight * $biggestSideSize) / $originalWidth;
			$src_x = 0;
			$src_y = ( $src_h - $biggestSideSize) / 2;
		}

		switch ( $info[2] ){
			case IMAGETYPE_GIF:
				$im = $im->coalesceImages();
				foreach ( $im as $newFileObj ) {
					
					$newFileObj->setFormat("gif");
					
					$new_x = 0;
					$new_y = 0;
					
					$tmp_new_width = $newWidth;
					$tmp_new_height = $newHeight;
					
					$imagePage = $newFileObj->getImagePage();
					
					# ширина и высота обрезаемой области
					# вертикальная картинка
					if ( $originalWidth < $originalHeight ) {
						$cutedWidth = $originalWidth;
						$cutedHeight = $originalWidth;
					} else { 
						#горизонтальная картинка
						$cutedWidth = $originalHeight;
						$cutedHeight = $originalHeight;
					}
					
					$resize_ratio = $cutedHeight / $biggestSideSize ;
					
					$offset_y = $imagePage['y'];
					
					# если размер кадра не совпадает с размером самой картинки
					if ( $newFileObj->getImageWidth() < $newWidth ) {
						$tmp_new_width = round( $newFileObj->getImageWidth() / $resize_ratio );
						$tmp_new_height = round( $newFileObj->getImageHeight() / $resize_ratio );
						
						$offset_x = $imagePage['x'];
						
						$new_x = round( $offset_x / $resize_ratio );
						$new_y = round( $offset_y / $resize_ratio );
					}  else if ( $newFileObj->getImageHeight() < $newHeight ) {
						$tmp_new_width = round( $newFileObj->getImageWidth() / $resize_ratio );
						$tmp_new_height = round( $newFileObj->getImageHeight() / $resize_ratio );
						
						$offset_x = $imagePage['x'] - ( $originalWidth - $cutedWidth )/2;
						
						$new_x = round( $offset_x / $resize_ratio );
						$new_y = round( $offset_y / $resize_ratio );
						
						#dbg( 'Кадр:' . $newFileObj->getImageWidth() . 'x' . $newFileObj->getImageHeight() );
						#dbg( 'Уменьшен до:' . $tmp_new_width . 'x' . $tmp_new_height );
						#dbg( 'Будет размещен: ' . $new_x . ',' . $new_y . ' в поле ' . $biggestSideSize . 'x' . $biggestSideSize );
					}
					
					//Выполняется resize до 200 пикселей поширине и сколько получится по высоте (с соблюдением пропорций конечно)
					$newFileObj->thumbnailImage( $tmp_new_width, $tmp_new_height );
					
					#dbg('Кропнуть: ' . $biggestSideSize . 'x' . $biggestSideSize . ' коорд. ' . $src_x . ',' . $src_y );
					#dbg( 'Кадр до кропа:' . $newFileObj->getImageWidth() . 'x' . $newFileObj->getImageHeight() );
					
					if ( $newFileObj->getImageHeight() >= $biggestSideSize || $newFileObj->getImageWidth() >= $biggestSideSize ) {
						$newFileObj->cropImage( $biggestSideSize, $biggestSideSize, $src_x, $src_y );
					}
					else {
						$newFileObj->cropImage( $biggestSideSize, $biggestSideSize, 0, $src_y );
					}
					
					$newFileObj->setImagePage( $newFileObj->getImageWidth(), $newFileObj->getImageHeight(), $new_x, $new_y );
					
					#dbg( 'Кадр после кропа:' . $newFileObj->getImageWidth() . 'x' . $newFileObj->getImageHeight() );
					
					#dbg('........................');
				}
				
				$newFileObj->writeImages( $destinationFile, true);
				return image_type_to_extension( $info[2], false );
				break;

			case IMAGETYPE_PNG:
				$im->thumbnailImage( $newWidth, $newHeight );
				$im->cropImage( $biggestSideSize, $biggestSideSize, $src_x, $src_y );
				#$im->setImageCompressionQuality(90);
                $im->sharpenImage(4, 1);
				$im->writeImages( $destinationFile, true);
				return image_type_to_extension( $info[2], false );
				break;

			case IMAGETYPE_JPEG:
				$im->thumbnailImage( $newWidth, $newHeight );
				$im->cropImage( $biggestSideSize, $biggestSideSize, $src_x, $src_y );
				$im->setImageCompressionQuality(95);
                $im->sharpenImage(4, 1);                
				$im->writeImages( $destinationFile, true);
				return image_type_to_extension( $info[2], false );
				break;
		}
	}

	switch ( $info[2] ){
		case IMAGETYPE_PNG:
			$newFileObj->setFormat("png");
			$im->thumbnailImage( $newWidth, $newHeight );
			return  $im->writeImages( $destinationFile, true);
			break;

		case IMAGETYPE_JPEG:
			$newFileObj->setFormat("jpeg");
			$im->thumbnailImage( $newWidth, $newHeight );
//            $matrix = array(array(-1,-1,-1), array(-1,16,-1), array(-1,-1,-1));
//	        imageconvolution($destinationFile, $matrix, 8, 0);
			return  $im->writeImages( $destinationFile, true);
			break;

		case IMAGETYPE_GIF:
			$newFileObj->setFormat("gif");

			foreach ( $im as $newFileObj ) {
//			 echo $newWidth . $newHeight . "<--";
				//Выполняется resize до 200 пикселей поширине и сколько получится по высоте (с соблюдением пропорций конечно)
				$newFileObj->thumbnailImage( $newWidth, $newHeight );
				$newFileObj->setImagePage( $newFileObj->getImageWidth(), $newFileObj->getImageHeight(), 0, 0 );
			}
			return  $newFileObj->writeImages( $destinationFile, true);
			break;

		default:
			return false;
	}
}

}
?>