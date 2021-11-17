require('./bootstrap');

import Alpine from 'alpinejs';


Alpine.data('emailCollectorApp', function () {
    return {
        page: 'main',

        reloadLists(){
            this.api.getLists().then(res => {
                this.listsArr = res.data;
            });
        },

        navigate(page, params = {}) {

            this.page = page;

            this.$nextTick(() => {
                if(page === 'list-view') {
                    this.currentList = params.list;
                    this.listLoadData();
                }
    
                if(page === 'main') {
                    this.currentList = {};
                    this.reloadLists();
                }
            })
        },
        listLoadData(){
            this.api.showList(this.currentList.id).then(response => {
                this.currentList = response.data
            })
        },    
        listRemove(){
            if(this.currentList.removeReady != true){
                this.currentList.removeReady = true;
                return;
            }

            this.api.destroyList(this.currentList.id).then(response => {
                this.navigate('main');
            })
        },        
        listFormData: {
            name: '',
            domains: '',
        },
        listFormErrors: {
            name: [],
            domains: [],
        },
        listFormSubmitted: false,
        listFormSubmit(){
            if(this.listFormSubmitted) return; // prevent double submit

            this.listFormSubmitted = true;
            this.listFormErrors.name = [];
            this.listFormErrors.domains = [];

            const domainsArr = this.listFormData
                                        .domains.split("\n")
                                                .map(d => d.trim())
                                                .filter(d => d.length > 0);

            this.api.addList({
                name: this.listFormData.name,
                domains: domainsArr,
            })
            .then(response => {

                showToast('List added successfully');

                this.listsArr.push(response.data.result);

                this.listFormClear();

                this.navigate('main')

            })
            .catch(error => {
                const responseObj = error.response.data;
                
                for (const [fieldName, errors] of Object.entries(responseObj.errors)) {
                    if(fieldName.startsWith('domains.')) {
                        const rowNum = parseInt(fieldName.split('.')[1]) + 1;
                        this.listFormErrors.domains.push(`Domain #${rowNum} ${errors[0]}`);
                    } else {
                        this.listFormErrors[fieldName] = errors;
                    }
                }
                
            })
            .finally(() => {
                this.listFormSubmitted = false;
            })
        },
        listFormClear(){
            this.listFormData.name = '';
            this.listFormData.domains = '';
        },
        api: {
            getLists(){
                return axios.get('/api/lists');
            },
            showList(id){
                return axios.get(`/api/lists/${id}`);
            },
            destroyList(id){
                return axios.delete(`/api/lists/${id}`);
            },
            addList(list){
                return axios.post('/api/lists', list);
            }
        },

        init(){ // Runs after the page is loaded
            this.reloadLists();
        },

        listsArr: [
            /* {
                id: 1,
                name: 'List 1',
            }, */
        ],
        currentList: {
            id: 1,
            name: 'My',
            domains: [
                {
                    domain_name: 'abc.com',
                    contacts: [
                        {
                            id: 1,
                            first_name: 'John',
                            last_name: 'Doe',
                            email: 'johndoe@mail.tld',
                            confidence: 67,
                        }
                    ]
                },
                {
                    domain_name: 'another.com',
                    contacts: [
                        {
                            id: 1,
                            first_name: 'John',
                            last_name: 'Doe',
                            email: 'johndoe@mail.tld',
                            confidence: 67,
                        },
                        {
                            id: 1,
                            first_name: 'John 2',
                            last_name: 'Doe 2',
                            email: 'johndoe@mail.tld',
                            confidence: 67,
                        }
                    ]
                }
            ]
        },
        
    }
})

Alpine.start()