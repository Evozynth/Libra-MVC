<div class="box">
    <h4>All modules</h4>
    <p>All Libra modules.</p>
    <ul>
        <?php foreach ($modules as $module): ?>
        <li><a href='<?=create_url("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="box">
    <h4>Libra core</h4>
    <p>Libra core modules.</p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if ($module['isLibraCore']): ?>
            <li><a href='<?=create_url("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<div class="box">
    <h4>Libra CMF</h4>
    <p>Libra Content Management Framework (CMF) modules.</p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if ($module['isLibraCMF']): ?>
                <li><a href='<?=create_url("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<div class="box">
    <h4>Models</h4>
    <p>A class is considered a model if its name starts with CM.</p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if ($module['isModel']): ?>
                <li><a href='<?=create_url("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<div class="box">
    <h4>Controllers</h4>
    <p>Implements interface <code>IController</code>.</p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if ($module['isController']): ?>
                <li><a href='<?=create_url("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<div class="box">
    <h4>Manageable modules</h4>
    <p>Implements interface <code>IModule</code>.</p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if ($module['isManageable']): ?>
                <li><a href='<?=create_url("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<div class="box">
    <h4>Contains SQL</h4>
    <p>Implements interface <code>IHasSQL</code>.</p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if ($module['hasSQL']): ?>
                <li><a href='<?=create_url("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<div class="box">
    <h4>More modules</h4>
    <p>Modules that does not implement any specific Libra interface.</p>
    <ul>
        <?php foreach ($modules as $module): ?>
            <?php if (!($module['isController'] || $module['isLibraCore'] || $module['isLibraCMF'])): ?>
                <li><a href='<?=create_url("modules/view/{$module['name']}")?>'><?=$module['name']?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>