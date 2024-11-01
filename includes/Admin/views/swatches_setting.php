    <div class="my-5">
    <div class=" md:grid md:grid-cols-3 md:grid-6">
        <div class=" md:col-span-2">
        <div class="bg-white mr-5 rounded shadow p-5">
            <h1 class="text-gray-600 text-xl md:text-2xl">Variation Swatches Settings</h1>
            <div>
            <form action="options.php" method="POST">
                <?php
                    settings_fields( 'basic_settings_option_group' );
                    do_settings_sections( 'basic_settings' );
                    submit_button();
                ?>
            </form>
        </div>
        </div>
        </div>
        <div class=" md:col-span-1">
        <div class="bg-white max-w-md p-5 rounded shadow">
            <div class="flex items-center">
                <svg class="bg-blue-800 h-6 p-1 rounded-full text-white w-6 m-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <h4 class=" text-base">Documentation</h4>
            </div>
            <p class="mx-1 -mt-1 mb-4 text-sm">Get start by WPC Variations Swatches for WooCommerce with Documentation</p>
            <a href="#" class="bg-blue-500 font-bold no-underline px-2 py-2 rounded text-white">Documentation</a>
        </div>
        </div>
    </div>
    </div>
