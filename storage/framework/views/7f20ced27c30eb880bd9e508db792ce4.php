<?php
    $churchDetails = \App\Models\AboutPage::getActiveChurchDetails();
?>

<div class="topbar-one">
    <div class="container-fluid">
        <div class="topbar-one__inner">
            <ul class="list-unstyled topbar-one__info">
                <?php if($churchDetails && $churchDetails->email_address): ?>
                <li class="topbar-one__info__item">
                    <span class="topbar-one__info__icon icon-paper-plane"></span>
                    <a href="mailto:<?php echo e($churchDetails->email_address); ?>"><?php echo e($churchDetails->email_address); ?></a>
                </li>
                <?php endif; ?>
                <?php if($churchDetails && $churchDetails->address): ?>
                <li class="topbar-one__info__item">
                    <span class="topbar-one__info__icon icon-location"></span>
                    <?php echo e($churchDetails->address); ?>

                </li>
                <?php endif; ?>
            </ul><!-- /.list-unstyled topbar-one__info -->
            <div class="topbar-one__right">
                <div class="social-link topbar-one__social">
                    <?php if($churchDetails && $churchDetails->social_media_links): ?>
                        <?php $__currentLoopData = $churchDetails->social_media_links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($url): ?>
                                <a href="<?php echo e($url); ?>" target="_blank" rel="noopener">
                                    <?php switch(strtolower($platform)):
                                        case ('facebook'): ?>
                                            <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                            <span class="sr-only">Facebook</span>
                                            <?php break; ?>
                                        <?php case ('twitter'): ?>
                                            <i class="fab fa-twitter" aria-hidden="true"></i>
                                            <span class="sr-only">Twitter</span>
                                            <?php break; ?>
                                        <?php case ('linkedin'): ?>
                                            <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                                            <span class="sr-only">Linkedin</span>
                                            <?php break; ?>
                                        <?php case ('youtube'): ?>
                                            <i class="fab fa-youtube" aria-hidden="true"></i>
                                            <span class="sr-only">Youtube</span>
                                            <?php break; ?>
                                        <?php case ('instagram'): ?>
                                            <i class="fab fa-instagram" aria-hidden="true"></i>
                                            <span class="sr-only">Instagram</span>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <i class="fab fa-<?php echo e(strtolower($platform)); ?>" aria-hidden="true"></i>
                                            <span class="sr-only"><?php echo e(ucfirst($platform)); ?></span>
                                    <?php endswitch; ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        
                        <a href="https://facebook.com">
                            <i class="fab fa-facebook-f" aria-hidden="true"></i>
                            <span class="sr-only">Facebook</span>
                        </a>
                        <a href="https://twitter.com">
                            <i class="fab fa-twitter" aria-hidden="true"></i>
                            <span class="sr-only">Twitter</span>
                        </a>
                        <a href="https://linkedin.com" aria-hidden="true">
                            <i class="fab fa-linkedin-in"></i>
                            <span class="sr-only">Linkedin</span>
                        </a>
                        <a href="https://youtube.com" aria-hidden="true">
                            <i class="fab fa-youtube"></i>
                            <span class="sr-only">Youtube</span>
                        </a>
                    <?php endif; ?>
                </div><!-- /.topbar-one__social -->
            </div><!-- /.topbar-one__right -->
        </div><!-- /.topbar-one__inner -->
    </div><!-- /.container -->
</div>
<?php /**PATH C:\Users\kenos\Documents\Github\citylife\resources\views/components/lighthouse.blade.php ENDPATH**/ ?>