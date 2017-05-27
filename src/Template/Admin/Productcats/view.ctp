<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Productcat'), ['action' => 'edit', $productcat->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Productcat'), ['action' => 'delete', $productcat->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productcat->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Productcats'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Productcat'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Stores'), ['controller' => 'Stores', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Store'), ['controller' => 'Stores', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="productcats view large-9 medium-8 columns content">
    <h3><?= h($productcat->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($productcat->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Store') ?></th>
            <td><?= $productcat->has('store') ? $this->Html->link($productcat->store->name, ['controller' => 'Stores', 'action' => 'view', $productcat->store->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($productcat->id) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Products') ?></h4>
        <?php if (!empty($productcat->products)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Store Id') ?></th>
                <th><?= __('Category') ?></th>
                <th><?= __('Product Name') ?></th>
                <th><?= __('Purchase Count') ?></th>
                <th><?= __('Productcat Id') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($productcat->products as $products): ?>
            <tr>
                <td><?= h($products->id) ?></td>
                <td><?= h($products->store_id) ?></td>
                <td><?= h($products->category) ?></td>
                <td><?= h($products->product_name) ?></td>
                <td><?= h($products->purchase_count) ?></td>
                <td><?= h($products->productcat_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Products', 'action' => 'view', $products->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Products', 'action' => 'edit', $products->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Products', 'action' => 'delete', $products->id], ['confirm' => __('Are you sure you want to delete # {0}?', $products->id)]) ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
