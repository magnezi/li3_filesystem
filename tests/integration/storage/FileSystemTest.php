<?php

namespace li3_filesystem\tests\integration\storage;

/**
 * Lithium Filesystem: managing file uploads the easy way
 *
 * @copyright     Copyright 2012, Little Boy Genius (http://www.littleboygenius.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use li3_filesystem\extensions\storage\FileSystem;
use lithium\core\Libraries;

class FileSystemTest extends \lithium\test\Integration {

	protected $tmpPath;

	public function setUp() {
		FileSystem::reset();
		$this->tmpPath = Libraries::get(true, 'resources') . '/tmp/tests';
	}

	public function tearDown() {
		FileSystem::reset();
	}

	public function testBasicFileSystemConfig() {
		$result = FileSystem::config();
		$this->assertFalse($result);

		$config = array('default' => array('adapter' => '\some\adapter', 'filters' => array()));
		$result = FileSystem::config($config);
		$this->assertNull($result);

		$expected = $config;
		$result   = FileSystem::config();
		$this->assertEqual($expected, $result);

		$result = FileSystem::reset();
		$this->assertNull($result);

		$config = array('default' => array('adapter' => '\some\adapter', 'filters' => array()));
		FileSystem::config($config);

		$result   = FileSystem::config();
		$expected = $config;
		$this->assertEqual($expected, $result);

		$result = FileSystem::reset();
		$this->assertNull($result);

		$config = array('default' => array(
			'adapter' => '\some\adapter',
			'filters' => array('Filter1', 'Filter2')
		));
		FileSystem::config($config);

		$result   = FileSystem::config();
		$expected = $config;
		$this->assertEqual($expected, $result);
	}

	public function testFileSystemWrite() {
		$config = array('default' => array(
			'adapter' => 'File',
			'filters' => array(),
			'path'    => $this->tmpPath
		));
		FileSystem::config($config);

		$result = FileSystem::config();
		$this->assertEqual($config, $result);

		$filename = 'test_file';
		$data     = 'Some test content';

		$this->assertTrue(FileSystem::write('default', $filename, $data));
		$this->assertFalse(FileSystem::write('non_existing', $filename, $data));
	}

	public function testFileSystemRead() {
		$config = array('default' => array(
			'adapter' => 'File',
			'filters' => array(),
			'path'    => $this->tmpPath
		));

		FileSystem::config($config);
		$result = FileSystem::config();
		$this->assertEqual($config, $result);

		$filename = 'test_file';
		$data     = 'Some Test content';

		$result = FileSystem::write('default', $filename, $data);
		$this->assertTrue($result);

		$result = FileSystem::read('default', $filename);
		$this->assertEqual($data, $result);
	}
}

?>