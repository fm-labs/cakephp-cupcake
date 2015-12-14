<?php if ($user): ?>
<div>
    <?= __d('banana','Logged in as {0}', $user['name']); ?>
</div>
<?php else: ?>
    <?= __d('banana','Not logged in'); ?>
<?php endif; ?>