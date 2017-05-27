<?= $this->element('header') ?>
<div id="base">
    <div class="offcanvas"></div>
    <div id="content">
        <section>
            <div class="section-body">
                <div class="add_new"><?= $this->Html->link(__('Add New Customers'), ['action' => 'add']) ?> </div>
                <h2 class="text-primary">Customers Table</h2><br/>
                <div class="col-lg-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th><?= $this->Paginator->sort('id') ?></th>
                                        <th><?= $this->Paginator->sort('store_id') ?></th>
                                        <th><?= $this->Paginator->sort('contact_no') ?></th>
                                        <th><?= $this->Paginator->sort('email') ?></th>
                                        <th><?= $this->Paginator->sort('name') ?></th>
                                        <th><?= $this->Paginator->sort('gender') ?></th>
                                        <th class="actions"><?= __('Actions') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customers as $customer): ?>
                                        <tr>
                                            <td><?= $this->Number->format($customer->id) ?></td>
                                            <td><?= $customer->has('store') ? $this->Html->link($customer->store->name, ['controller' => 'Stores', 'action' => 'view', $customer->store->id]) : '' ?></td>
                                            <td><?= $this->Number->format($customer->contact_no) ?></td>
                                            <td><?= h($customer->email) ?></td>
                                            <td><?= h($customer->name) ?></td>
                                            <td><?php
                                                if ($customer->gender == 1) {
                                                    echo "MALE";
                                                } else {
                                                    echo "FEMALE";
                                                }
                                                ?></td>
                                            <td class="actions">
                                                <?php echo $this->Html->link(__('View'), ['action' => 'view', $customer->id]) ?>
                                                <?php echo $this->Html->link(__('Edit'), ['action' => 'edit', $customer->id]) ?>
                                                <?php echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $customer->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customer->id)]) ?>
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