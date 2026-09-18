<?php
defined('_JEXEC') or die;
use Joomla\CMS\Uri\Uri;

$allowedLimits = json_decode($this->album->allowed_counts, true) ?: [];
?>

<script>
    const printLimits = <?php echo json_encode($allowedLimits); ?>;
    let currentSelections = {};

    function updateCounters() {
        let counts = {};
        Object.keys(printLimits).forEach(size => counts[size] = 0);

        document.querySelectorAll('.size-select').forEach(select => {
            let selectedSize = select.value;
            if (selectedSize && counts[selectedSize] !== undefined) {
                counts[selectedSize]++;
            }
        });

        Object.keys(printLimits).forEach(size => {
            let remaining = printLimits[size] - counts[size];
            let badge = document.getElementById('counter-' + size);
            if (badge) {
                badge.innerText = remaining;
                badge.className = remaining < 0 ? 'badge bg-danger' : 'badge bg-primary';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', updateCounters);
</script>

<div class="container py-4">
    <?php if (!$this->isAuthenticated): ?>
        <!-- Password Auth Form -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">Protected Album Access</div>
                    <div class="card-body">
                        <form method="post" action="<?php echo Uri::current(); ?>">
                            <div class="mb-3">
                                <label for="album_password" class="form-label">Enter Access Password</label>
                                <input type="password" name="album_password" id="album_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Access Gallery</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Client Ordering Portal -->
        <h2><?php echo htmlspecialchars($this->album->title); ?></h2>

        <!-- Top Counter Bar -->
        <div class="sticky-top bg-light p-3 border rounded shadow-sm mb-4">
            <h5 class="mb-2">Remaining Print Allowance:</h5>
            <div class="d-flex gap-3">
                <?php foreach ($allowedLimits as $size => $limit): ?>
                    <div class="fs-5">
                        <strong><?php echo htmlspecialchars($size); ?>:</strong> 
                        <span id="counter-<?php echo htmlspecialchars($size); ?>" class="badge bg-primary"><?php echo $limit; ?></span> left
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Photo Grid Form -->
        <form action="<?php echo Uri::root(); ?>index.php?option=com_photoorders&task=order.submit" method="post">
            <input type="hidden" name="album_id" value="<?php echo $this->album->id; ?>">
            
            <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
                <?php foreach ($this->photos as $photo): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="<?php echo htmlspecialchars($photo['url'], ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" style="height: 220px; object-fit: cover;">
                            <div class="card-body">
                                <p class="card-text text-truncate small"><code><?php echo htmlspecialchars($photo['name']); ?></code></p>
                                <select name="photos[<?php echo htmlspecialchars($photo['relative_path']); ?>]" 
                                        class="form-select size-select" onchange="updateCounters()">
                                    <option value="">-- No Print Requested --</option>
                                    <?php foreach (array_keys($allowedLimits) as $size): ?>
                                        <option value="<?php echo htmlspecialchars($size); ?>"><?php echo htmlspecialchars($size); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100">Complete &amp; Submit Order</button>
        </form>
    <?php endif; ?>
</div>