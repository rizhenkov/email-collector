<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ mix('css/app.css')  }}">
</head>

<body class="">

    <header class="bg-white p-3">
        <h1 class="text-center font-bold">{{ config('app.name') }}</h1>
    </header>

    <main class="p-5 pt-10" x-data="emailCollectorApp" x-cloak>

        <div class="app-page" x-show="page === 'list-new'" x-transition:enter.scale.origin.top
            x-transition:leave.scale.origin.top>

            <div class="page-toolbar">
                <span class="toolbar-heading">Upload list</span>

                <button @click="navigate('main')" class="btn btn-secondary">Cancel</button>
            </div>

            <div class="page-inner-area">

                <div class="mb-3">
                    <label class="form-label" for="input-name">Name</label>
                    <input x-model="listFormData.name" class="form-control" id="input-name" type="text">

                    <div class="invalid-feedback" x-show="listFormErrors.name">
                        <span x-text="listFormErrors.name[0]"></span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="input-domains">URLs/domains list <span class="text-gray-500">(one per
                            row)</span></label>
                    <textarea x-model="listFormData.domains" class="form-control w-full" id="input-domains"
                        rows="10"></textarea>

                    <div class="invalid-feedback" x-show="listFormErrors.domains">
                        <template x-for="error in listFormErrors.domains">
                            <div x-text="error"></div>
                        </template>
                    </div>
                </div>

                <button @click="listFormSubmit()" class="btn btn-primary">Upload</button>

            </div>

        </div>

        <div class="app-page" x-show="page == 'list-view'" x-transition:enter.scale.origin.top
            x-transition:leave.scale.origin.top>

            <div class="page-toolbar">
                <div class="toolbar-heading">
                    <a @click.prevent="navigate('main')" class="text-indigo" href="#">Your Lists</a> /
                    <span x-text="currentList.name"></span>
                </div>

                <div>
                    <button @click="listRemove()" @click.away="currentList.removeReady = false"
                        x-bind:class="{confirm: currentList.removeReady}" class="btn btn-secondary">Remove</button>

                    <button @click="navigate('main')" class="btn btn-secondary">Back</button>
                </div>
            </div>

            <div class="page-inner-area">

                <template x-if="! currentList.domains">
                    <div>Loading...</div>
                </template>

                <template x-if="currentList.domains && currentList.domains.length === 0">
                    <div>No domains in current list</div>
                </template>

                <template x-for="domain in currentList.domains">
                    <div class="mb-3">
                        <div class="text-xl mb-3" x-text="domain.domain_name"></div>

                        <div class="flex flex-wrap gap-3">
                            <template x-if="! domain.contacts">
                                <div>No contacts collected (yet)</div>
                            </template>

                            <template x-for="contact in domain.contacts">
                                <div class="p-3 rounded-md shadow-sm bg-white">
                                    <div class="text-md font-bold">
                                        <span x-text="(contact.first_name ? contact.first_name : 'Unnamed')"></span>
                                        <span x-text="contact.last_name"></span>
                                    </div>
                                    <div>
                                        <span x-text="contact.email"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

            </div>

        </div>
        <div class="app-page" x-show="page === 'main'" x-transition:enter.opacity
            x-transition:leave.scale.origin.bottom>

            <div class="page-toolbar">
                <span class="toolbar-heading">Your Lists</span>

                <button @click="navigate('list-new')" class="btn btn-primary">New list</button>
            </div>

            <div class="page-inner-area lists">

                <template x-if="! listsArr">
                    <div>
                        <div class="text-center">
                            <div class="text-lg font-bold">You don't have any lists yet.</div>
                            <div class="text-gray-500">Create a new one to get started.</div>
                        </div>
                    </div>
                </template>

                <template x-for="list in listsArr">

                    <div class="list-entry" @click="navigate('list-view', {list})">
                        <div class="text-xl" x-text="list.name"></div>
                    </div>

                </template>

            </div>
        </div>


    </main>

    <footer class="container">

        <p class="text-center text-gray-600">
            Hunter API Quota used ({{ $used }} / 25)
        </p>

    </footer>

    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
