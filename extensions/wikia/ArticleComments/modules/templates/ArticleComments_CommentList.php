<ul id="article-comments-ul" class="comments">
	<?php
	if (count($commentListRaw)) {
		$odd = true;
		foreach ($commentListRaw as $commentId => $commentArr) {
			$rowClass = $odd ? 'odd' : 'even';
			$odd = !$odd;
			if (isset ($commentArr['level1']) && $commentArr['level1'] instanceof ArticleComment) {
				$comment = $commentArr['level1']->getData($useMaster);
				echo wfRenderPartial('ArticleComments', 'Comment', array('comment' => $comment, 'commentId' => $commentId, 'rowClass' => $rowClass));
			}
			if (isset($commentArr['level2'])) {
				echo "<ul class=\"sub-comments\">\n";
				foreach ($commentArr['level2'] as $commentId => $reply) {
					if ($reply instanceof ArticleComment) {
						$comment = $reply->getData($useMaster);
						echo wfRenderPartial('ArticleComments', 'Comment', array('comment' => $comment, 'commentId' => $commentId, 'rowClass' => $rowClass));
					}
				}
				echo "</ul>";
			}
		}
	}
	?>
</ul>
