<?php if ($user): ?>
<div>
    <?= __('Logged in as {0}', $user['name']); ?>
</div>
<?php else: ?>
    <?= __('Not logged in'); ?>
<?php endif; ?>