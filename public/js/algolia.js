(function() {
    var client = algoliasearch('KV84C6LQ6L', '1d2941d117d281701fdd3eebd06f8f78');
    var index = client.initIndex('dev_ev');
    var enterPressed = false;
    //initialize autocomplete on search input (ID selector must match)
    autocomplete('#aa-search-input',
        { hint: false }, {
            source: autocomplete.sources.hits(index, { hitsPerPage: 10 }),
            //value to be displayed in input control after user's suggestion selection
            displayKey: 'name',
            //hash of templates used when rendering dataset
            templates: {
                //'suggestion' templating function used to render a single suggestion
                suggestion: function (suggestion) {
                    const markup = `
                        <div class="algolia-result">
                            <span>
                                <img src="" alt="img" class="algolia-thumb">
                               
                            </span>
                            <span>${suggestion.price} DH</span>
                        </div>
                        <div class="algolia-details">
                            <span>${suggestion.name}</span>
                        </div>
                    `;

                    return markup;
                },
                empty: function (result) {
                    return 'Sorry, we did not find any results for "' + result.query + '"';
                }
            }
        }).on('autocomplete:selected', function (event, suggestion, dataset) {
            window.location.href = window.location.origin + '/shop/' + suggestion.slug;
            enterPressed = true;
        }).on('keyup', function(event) {
            if (event.keyCode == 13 && !enterPressed) {
                window.location.href = window.location.origin + '/search-algolia?q=' + document.getElementById('aa-search-input').value;
            }
        });
})();
