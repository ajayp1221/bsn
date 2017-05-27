<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body">
                <div class="add_new"><?= $this->Html->link(__('Add New Brands'), ['action' => 'add']) ?> </div>
                <h2 class="text-primary">Brands Table</h2><br/>
                <div class="col-lg-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th><?= $this->Paginator->sort('id') ?></th>
                                        <th><?= $this->Paginator->sort('client_id') ?></th>
                                        <th><?= $this->Paginator->sort('brand_name') ?></th>
                                        <th><?= $this->Paginator->sort('status') ?></th>
                                        <th><?= $this->Paginator->sort('created') ?></th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($brands as $brand): ?>
                                        <tr>
                                            <td><?= $this->Number->format($brand->id) ?></td>
                                            <td><?= $brand->has('client') ? $this->Html->link($brand->client->name, ['controller' => 'Clients', 'action' => 'view', $brand->client->id]) : '' ?></td>
                                            <td><?= h($brand->brand_name) ?></td>
                                            <td><?php
                                                if ($brand->status) {
                                                    echo "Active";
                                                } else {
                                                    echo "Inactive";
                                                }
                                                ?>
                                            </td>
                                            <td><?= date("Y-M-d H:i", $brand->created) ?></td>
                                            <td class="actions">
                                                <?php echo $this->Html->link(__('Edit'), ['action' => 'edit', $brand->id]) ?>
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
