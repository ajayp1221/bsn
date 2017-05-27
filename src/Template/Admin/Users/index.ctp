<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body">
                <div class="add_new"><?= $this->Html->link(__('Add New User'), ['action' => 'add']) ?> </div>
                <h2 class="text-primary">Users Table</h2><br/>
                <?php echo $this->Form->create(NULL, ['type' => 'get', 'class' => 'form-horizontal']) ?>
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="input-group">
                            <div class="input-group-content">
                                <input type="text" name="q" placeholder="Search Here" id="groupbutton17" class="form-control">
                                <div class="form-control-line"></div>
                            </div>
                            <div class="input-group-btn">
                                <?php echo $this->Form->button(__('Search!'), ['class' => 'btn btn-success']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end() ?>
                <div class="col-lg-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th><?= $this->Paginator->sort('id') ?></th>
                                        <th><?= $this->Paginator->sort('name') ?></th>
                                        <th><?= $this->Paginator->sort('email') ?></th>
                                        <th><?= $this->Paginator->sort('roles') ?></th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?= $this->Number->format($user->id) ?></td>
                                            <td><?= h($user->first_name) ?></td>
                                            <td><?= h($user->email) ?></td>
                                            <td><?= h($user->roles) ?></td>
                                            <td class="actions">
                                                <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>
                                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]); ?>
                                                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="paginator">
                    <ul class="pagination">
                        <?= $this->Paginator->prev('< ' . __('previous')) ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(__('next') . ' >') ?>
                    </ul>
                    <p><?= $this->Paginator->counter() ?></p>
                </div>
            </div>
        </section>
    </div>
    <?= $this->element('side') ?>
</div>
<?= $this->element('tablejs') ?>
