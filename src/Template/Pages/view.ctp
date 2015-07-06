<?php
$this->assign('title', $page->title);
echo $this->cell('Banana.Page', ['pageId' => $page->id]);

debug($page);