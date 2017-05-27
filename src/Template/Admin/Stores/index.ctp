<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body">
                <div class="add_new"><?= $this->Html->link(__('Add New Zakoopi Store'), ['action' => 'add']) ?><br/>
                <?= $this->Html->link(__('Add New Store'), ['action' => 'add1']) ?></div>
                <h2 class="text-primary">Stores Table</h2><br/>
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
                                        <th><?= $this->Paginator->sort('status') ?></th>
                                        <th><?= $this->Paginator->sort('created') ?></th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stores as $store): ?>
                                        <?php $store->slug ?>
                                        <tr>
                                            <td><?= $this->Number->format($store->id) ?></td>
                                            <td><?= h($store->name) ?><br>
                                                <a href="/test/switchme/<?= base64_encode(base64_encode(base64_encode($store->brand->client_id))) ?>">Visit as Client</a><br>
                                                <a href="/admin/stores/addIosCert/<?= $this->Number->format($store->id) ?>">Add iOS .pem</a>
                                            </td>
                                            <td><?php
                                                if ($store->status) {
                                                    echo "active";
                                                } else {
                                                    echo "deactive";
                                                }
                                                ?></td>
                                            <td><?= date("Y-M-d H:i", $store->created) ?></td>

                                            <td class="actions">
                                                <?= $this->Html->link(__('View'), ['action' => 'view', $store->id]) ?>
                                                <?php echo $this->Html->link(__('Edit'), ['action' => 'edit', $store->id]) ?>
                                                <?php // echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $store->id], ['confirm' => __('Are you sure you want to delete # {0}?', $store->id)]) ?>
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
