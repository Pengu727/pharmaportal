<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Board</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen antialiased pb-12 font-sans">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">💚</span>
                    <span class="font-bold text-lg text-slate-800 tracking-tight">PharmaPortal <span class="text-xs text-emerald-600 font-mono bg-emerald-50 px-2 py-0.5 rounded border border-emerald-500/10 ml-1">Admin</span></span>
                </div>
                <div class="w-9 h-9 bg-slate-800 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm" title="<?php echo e(session('admin_name', 'Admin')); ?>">
                    <?php echo e(substr(session('admin_name', 'A'), 0, 1)); ?>

                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Registered Pharmacies</h1>
                <p class="text-xs text-slate-400 mt-0.5">Manage licenses, configurations, and core localization files inside MongoDB</p>
            </div>
            
            <a href="<?php echo e(route('admin.create')); ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-5 py-3 rounded-xl transition shadow-md shadow-emerald-600/10 flex items-center space-x-2">
                <span>➕</span> <span>Add New Pharmacy</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6">Pharmacy Establishment Name</th>
                            <th class="py-4 px-6">Assigned Owner</th>
                            <th class="py-4 px-6">Location Mapping Context</th>
                            <th class="py-4 px-6 text-center">Execution Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                         <?php $__currentLoopData = $pharmacies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    <a href="<?php echo e(route('admin.show', ['id' => (string)$p->_id])); ?>" class="block font-bold text-slate-800 hover:text-emerald-600 transition">
                                        <?php echo e($p->nom_pharmacie ?? 'Unnamed Pharmacy'); ?>

                                    </a>
                                    <span class="text-[11px] text-slate-400 font-mono block"><?php echo e($p->registre_commerce ?? 'No RC data'); ?></span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-slate-700"><?php echo e($p->nom); ?> <?php echo e($p->prenom); ?></span>
                                    <span class="text-xs text-slate-400 block"><?php echo e($p->email); ?></span>
                                </td>
                                <td class="py-4 px-6 text-slate-500">
                                    <span class="text-xs font-semibold text-slate-700 block"><?php echo e($p->commune); ?></span>
                                    <span class="text-[11px] text-slate-400 block"><?php echo e($p->wilaya); ?> • Algeria</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="<?php echo e(route('admin.show', ['id' => (string)$p->_id])); ?>" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                                            👁️ View
                                        </a>
                                        <a href="<?php echo e(route('admin.edit', ['id' => (string)$p->_id])); ?>" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white font-bold text-xs rounded-xl transition border border-emerald-500/10">
                                            ✏️ Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>
</html><?php /**PATH /home/pengu/Documents/PFE/software/pharmacy-app/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>