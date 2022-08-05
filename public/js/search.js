       
      const searchClient = algoliasearch('KV84C6LQ6L', '1d2941d117d281701fdd3eebd06f8f78');

      const search = instantsearch({
        indexName: 'dev_ev',
        searchClient,
      });

      search.addWidgets([
        instantsearch.widgets.searchBox({
          container: '#search-box',
        }),

        instantsearch.widgets.hits({
        container: '#hits',
        templates: {
          item: `
            <div class="col-lg-12 col-sm-12 col-xs-12" style="padding: 13px;margin-bottom: 20px;">
                <div class="col-lg-5 col-sm-5 col-xs-5 " style="padding: 0px 1px;"  >
                    <img src=""  alt="{{ name }}" style="width:100%;">
                </div>
                <div class="col-lg-7 col-sm-7 col-xs-7" style="    padding: 0px 5px;">
                    <h4 style=" min-height: 38px;  padding-bottom: 0px;  font-size: 15px;line-height: 18px;">{{ name }}</h4> 
                    <span class="small-box-title">
                       {{ price }}
                    </span> 
                </div>
            </div>
          `,
        },
      })
    ]);

      search.start();