<?php
require ROOT_PATH . '/frontend/views/layout/header.php';
$cid = (int) $character['id'];
?>
<h1 class="h3 mb-4">Character Details</h1>
<div class="row">
    <div class="col-lg-6 mb-4">
        <dl class="row">
            <dt class="col-sm-4">ID</dt>
            <dd class="col-sm-8"><?php echo e((string) $character['id']); ?></dd>
            <dt class="col-sm-4">Name</dt>
            <dd class="col-sm-8"><?php echo e($character['name']); ?></dd>
            <dt class="col-sm-4">Level</dt>
            <dd class="col-sm-8"><?php echo e((string) $character['level']); ?></dd>
            <dt class="col-sm-4">HP / Mana</dt>
            <dd class="col-sm-8"><?php echo e((string) $character['hp']); ?> / <?php echo e((string) $character['mana']); ?></dd>
            <dt class="col-sm-4">Kill By</dt>
            <dd class="col-sm-8"><?php echo e($character['kill_by_player_name'] ?? ''); ?></dd>
        </dl>
        <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(base_url()); ?>index.php?controller=character&action=index">List</a>
        <a class="btn btn-primary btn-sm" href="<?php echo e(base_url()); ?>index.php?controller=character&action=edit&id=<?php echo $cid; ?>">Edit</a>
    </div>
    <div class="col-lg-6">
        <h2 class="h5">Assigned Skills</h2>
        <ul class="list-group mb-3">
            <?php if (empty($skills)): ?>
                <li class="list-group-item text-muted">No skills assigned</li>
            <?php else: ?>
                <?php foreach ($skills as $s): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><?php echo e($s['name']); ?> <small class="text-muted">(DMG <?php echo e((string) $s['damage']); ?>, MP <?php echo e((string) $s['mana_cost']); ?>)</small></span>
                        <a class="btn btn-sm btn-outline-danger" href="<?php echo e(base_url()); ?>index.php?controller=character&action=detachSkill&character_id=<?php echo $cid; ?>&skill_id=<?php echo (int) $s['id']; ?>"
                           onclick="return confirm('Remove this skill?');">Remove</a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <?php if (!empty($allSkills)): ?>
        <form class="row g-2 align-items-end mb-4" method="post" action="<?php echo e(base_url()); ?>index.php?controller=character&action=attachSkill">
            <input type="hidden" name="character_id" value="<?php echo $cid; ?>">
            <div class="col-auto flex-grow-1">
                <label class="form-label small mb-0" for="skill_id">Add Skill</label>
                <select class="form-select form-select-sm" id="skill_id" name="skill_id" required>
                    <option value="">— Select skill —</option>
                    <?php foreach ($allSkills as $s): ?>
                        <option value="<?php echo (int) $s['id']; ?>"><?php echo e($s['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-success">Attach</button>
            </div>
        </form>
        <?php endif; ?>

        <h2 class="h5">Assigned Items</h2>
        <ul class="list-group mb-3">
            <?php if (empty($items)): ?>
                <li class="list-group-item text-muted">No items assigned</li>
            <?php else: ?>
                <?php foreach ($items as $it): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><?php echo e($it['name']); ?> <small class="text-muted"><?php echo e($it['type']); ?> — Power <?php echo e((string) $it['power']); ?></small></span>
                        <a class="btn btn-sm btn-outline-danger" href="<?php echo e(base_url()); ?>index.php?controller=character&action=detachItem&character_id=<?php echo $cid; ?>&item_id=<?php echo (int) $it['id']; ?>"
                           onclick="return confirm('Remove this item?');">Remove</a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <?php if (!empty($allItems)): ?>
        <form class="row g-2 align-items-end" method="post" action="<?php echo e(base_url()); ?>index.php?controller=character&action=attachItem">
            <input type="hidden" name="character_id" value="<?php echo $cid; ?>">
            <div class="col-auto flex-grow-1">
                <label class="form-label small mb-0" for="item_id">Add Item</label>
                <select class="form-select form-select-sm" id="item_id" name="item_id" required>
                    <option value="">— Select item —</option>
                    <?php foreach ($allItems as $it): ?>
                        <option value="<?php echo (int) $it['id']; ?>"><?php echo e($it['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-success">Attach</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>
<?php require ROOT_PATH . '/frontend/views/layout/footer.php'; ?>
