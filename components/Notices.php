<?php

namespace Enovision\MarkdownNotices\Components;

class Notices extends \Cms\Classes\ComponentBase {

	public function componentDetails() {
		return [
			'name'        => 'Notices',
			'description' => 'Just the CSS nothing more'
		];
	}

	public function onRun() {
		$this->addCss( '/plugins/enovision/markdownnotices/assets/css/notices.css' );
	}
}