<?php
/* Copyright (c) 1998-2015 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once 'Services/Administration/interfaces/interface.ilVersionControlInformation.php';

/**
 * Class ilGitInformation
 * @author Michael Jansen <mjansen@databay.de>
 */
class ilGitInformation implements ilVersionControlInformation
{
	/**
	 * @var string
	 */
	private static $revision_information = null;

	/**
	 *
	 */
	private static function detect()
	{
		/**
		 * @var $lng ilLanguage
		 */
		global $lng;

		if(null !== self::$revision_information)
		{
			return self::$revision_information;
		}

		$info = array();

		if(!ilUtil::isWindows())
		{
			// https://gist.github.com/reiaguilera/82d164c7211e299d63ac
			$version_mini_hash = ilUtil::execQuoted('git describe --always');
			$version_number    = ilUtil::execQuoted('git rev-list HEAD | wc -l');
			$line              = ilUtil::execQuoted('git log -1');

			if($version_number[0])
			{
				$info[] = sprintf($lng->txt('git_revision'), $version_number[0]);
			}

			if($version_mini_hash[0])
			{
				$info[] = sprintf($lng->txt('git_hash_short'), $version_mini_hash[0]);
			}

			if($line && array_filter($line))
			{
				$info[] = sprintf($lng->txt('git_last_commit'), implode(' | ', array_filter($line)));
			}
		}

		self::$revision_information = $info;
	}

	/**
	 * @return string
	 */
	public function getInformationAsHtml()
	{
		self::detect();

		return implode("<br />", self::$revision_information);
	}
}
