<?php
defined('_JEXEC') or die;
use Joomla\CMS\Uri\Uri;
?>

<div class="container-fluid">
    <h1>Client Photo Orders Report</h1>

    <?php if (!empty($this->orders)): ?>
        <?php foreach ($this->orders as $order): ?>
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <strong>Order #<?php echo $order->id; ?></strong> - Album: <?php echo htmlspecialchars($order->album_title); ?> 
                    <span class="float-end"><?php echo $order->created_at; ?></span>
                </div>
                <div class="card-body">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th style="width: 100px;">Thumbnail</th>
                                <th>Original File Name</th>
                                <th>Requested Print Size</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $items = json_decode($order->order_data, true);
                            foreach ($items as $item): 
                                $thumbUrl = Uri::root() . 'images/' . ltrim($item['relative_path'], '/');
                            ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($thumbUrl, ENT_QUOTES, 'UTF-8'); ?>" 
                                             alt="Thumbnail" class="img-thumbnail" style="max-height: 80px;">
                                    </td>
                                    <td><code><?php echo htmlspecialchars($item['filename']); ?></code></td>
                                    <td><span class="badge bg-success fs-6"><?php echo htmlspecialchars($item['size']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">No photo print orders have been placed yet.</div>
    <?php endif; ?>
</div>