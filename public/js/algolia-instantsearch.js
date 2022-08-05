(function() {
    const search = instantsearch({
        appId: 'KV84C6LQ6L',
        apiKey: '1d2941d117d281701fdd3eebd06f8f78',
        indexName: 'dev_ev',
        urlSync: true
    });

    search.addWidget(
        instantsearch.widgets.hits({
            container: '#hits',
            templates: {
                empty: 'No results',
                item: function(item) {
                    return `
                        <a href="${window.location.origin}/${item.slug}">
                            <div class="instantsearch-result">
                                <div>
                                    <img src="${window.location.origin}/storage/${item.image}" alt="img" class="algolia-thumb-result">
                                </div>
                                <div>
                                    <div class="result-title">
                                        ${item.name}
                                    </div>
                                    <div class="result-details">
                                        
                                    </div>
                                    <div class="result-price">
                                        "${item.price } DH"
                                    </div>
                                </div>
                            </div>
                        </a>
                        <hr>
                    `;
                }
            }
        })
    );

    // initialize SearchBox
    search.addWidget(
        instantsearch.widgets.searchBox({
            container: '#search-box',
            placeholder: 'Search for products'
        })
    );

    // initialize pagination
    search.addWidget(
        instantsearch.widgets.pagination({
            container: '#pagination',
            maxPages: 20,
            // default is to scroll to 'body', here we disable this behavior
            scrollTo: false
        })
    );

    search.addWidget(
        instantsearch.widgets.stats({
            container: '#stats-container'
        })
    );

    // initialize RefinementList
    search.addWidget(
        instantsearch.widgets.refinementList({
            container: '#refinement-list',
            attributeName: 'categories',
            sortBy: ['name:asc']
        })
    );

    search.start();
})();
