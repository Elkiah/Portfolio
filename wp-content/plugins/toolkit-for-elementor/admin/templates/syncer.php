<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

<script type="text/javascript">
var keyVerified = <?= json_encode($keyVerified) ?>;
var syncerToken = '<?= $token ?>';

var prettyPermalink = false;
var endpoint = '/index.php?rest_route=/toolkit/v1/syncer';
var templatesPath = endpoint + '/templates&token=' + syncerToken;
var remoteTemplatesPath = endpoint + '/remote/templates&token=' + syncerToken;
<?php if (get_option('permalink_structure')) { ?>
	endpoint = '/wp-json/toolkit/v1/syncer';
	prettyPermalink = true;

	templatesPath = endpoint + '/templates?token=' + syncerToken;
	remoteTemplatesPath = endpoint + '/remote/templates?token=' + syncerToken;
<?php } ?>

var availableSites = <?= json_encode($availableSites) ?>;

function getTemplateUrl(id, remoteSite) {
	var separator = '&';
	if (prettyPermalink) {
		separator = '?';
	}

  if (remoteSite) {
    return endpoint + '/remote/template/' + id + separator + 'token=' + syncerToken + '&site=' + remoteSite;
  } else {
    return endpoint + '/template/' + id + separator + 'token=' + syncerToken;
  }
}

function getRemoteData(url) {
  return new Promise(function(resolve, reject) {
    jQuery.ajax({
      type: "GET",
      url: url,
      dataType: "json",
      success: function(response) {
        resolve(response);
      },
      error: function(request, status, error) {
        reject(error);
      }
    });
  });
}

// Utilities
function tkArrayUniq(a) {
	return a.filter(function(value, index){
		return a.indexOf(value) == index
	});
}

// Vue filters
Vue.filter('capitalize', function (value) {
  if (!value) return ''
  value = value.toString()
  return value.charAt(0).toUpperCase() + value.slice(1)
});
</script>

<script type="text/x-template" id="toolkit_syncer_loading">
  <p v-if="content">
    {{ content }}
  </p>
</script>
<script type="text/javascript">
Vue.component('loading', {
  template: '#toolkit_syncer_loading',
  props: ['el', 'content'],
  created: function() {
    if (this.el) {
      jQuery(this.el).showLoading();
    }
  },
  destroyed: function() {
    if (this.el) {
      jQuery(this.el).hideLoading();
    }
  },
})
</script>

<script type="text/x-template" id="toolkit_syncer_templates">
  <table class="table table-bordered" id="current-template-section" style="background:#F9F9F9;">
    <thead>
      <tr>
      <th width="50%">Title</th>
      <th width="10%">Type</th>
      <th width="20%">Created at</th>
      <th v-show="showAction" width="20%">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-if="loading">
        <loading el="#current-template-section" />
      </tr>

      <tr v-else v-for="entry in entries">
        <td>{{ entry.title }}</td>
        <td>{{ entry.type }}</td>
        <td>{{ entry.human_date }}</td>
        <td v-show="showAction">
          <button @click="downloadClicked(entry)">
            Download
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</script>
<script type="text/javascript">
Vue.component('template-listing', {
  template: '#toolkit_syncer_templates',
  props: ['entries', 'showAction', 'loading'],
  methods: {
    downloadClicked: function(entry) {
      this.$emit('download', entry);
    }
  },
})
</script>

<script type="text/x-template" id="toolkit_syncer_templates_tabs">
	<div class="template-listing-tabs">
		<h2 class="nav-tab-wrapper">
			<a v-for="tab in getTabs()" :href="tab" :class="{'nav-tab': true, 'nav-tab-active': selectedTab === tab}" @click="selectTab(tab, $event)">
				{{ tab | capitalize }}
			</a>
		</h2>

		<div class="tab-content">
			<ul class="templates-wrapper clearfix">
				<li v-for="entry in entries" :class="{template: true, hidden: (entry.type !== selectedTab && selectedTab !== 'all')}">
					<div>
						<h4>{{ entry.title }}</h4>
						<img src="<?php echo esc_url(plugins_url('images/syncer-template-thumbnail.png', dirname(__FILE__))) ?>" :title="entry.title" />
					</div>
					<h4>
						<button @click="downloadClicked(entry)">
							Import Template
						</button>
					</h4>
				</li>
			</ul>
		</div>
	</div>
</script>
<script type="text/javascript">
Vue.component('template-listing-tabs', {
  template: '#toolkit_syncer_templates_tabs',
  props: ['entries', 'showAction', 'loading'],
  data: function() {
		return {
			currentEntries: this.entries,
			selectedTab: 'all',
		};
  },
  methods: {
	getTabs: function() {
		return tkArrayUniq(['all'].concat(this.entries.map(function(entry) {
			return entry.type;
		})));
	},
	selectTab: function(tab, event) {
		event.preventDefault();

		// Change the tab
		this.selectedTab = tab;
	},
    downloadClicked: function(entry) {
      this.$emit('download', entry);
    }
  },
})
</script>

<script type="text/x-template" id="toolkit_syncer_remote_templates">
  <table class="table table-bordered" id="remote-template-section" style="background:#F9F9F9;">
    <tbody>
      <tr>
        <td class="site-cell">
		<h3>
			Session Manager
			<button class="button toolkit-btn toggle-button" @click="disablesyncer">
				Disable Syncer
			</button>
		</h3>

		<hr />
          <ul class="site-listing">
            <li v-for="site in sites" v-if="site.hide_syncer === '1'" @click="selectSite(site)" :class="{'selected': selectedSite && site.domain === selectedSite.domain}">
              {{ site.domain }}
            </li>
          </ul>
        </td>
        <td class="template-cell">
          <loading v-if="loading" el="#remote-template-section" />

          <div v-if="selectedSite && !loading">
            <template-listing-tabs v-if="templates.length > 0" :entries="templates" :showAction="true" @download="handleDownload"/>
          </div>

          <p v-if="templates.length === 0 && !loading && !selectedSite">
            Please select an external site to connect to.
          </p>

          <p v-if="templates.length === 0 && selectedSite">
			Sorry, we didn’t find any Elementor templates on the selected site.
          </p>

        </td>
      </tr>
    </tbody>
  </table>
</script>
<script type="text/javascript">
Vue.component('remote-templates', {
  template: '#toolkit_syncer_remote_templates',
  props: ['disablesyncer'],
  data: function() {
    return {
      sites: availableSites,
      selectedSite: null,
      loading: false,
      templates: [],
    }
  },
  methods: {
    selectSite: function(site) {
      var vm = this;

      // Check if its current loading or not
      if (vm.loading) {
        alert('Syncer connection in progress, please allow the current operation to finish');
        return true;
      }

	  // Check if the its localhost or not
	  if (site.site_url && site.site_url.indexOf('localhost') !== -1) {
        alert('Syncer has detected that this site is on a local environment and thus cannot be connected to.');
        return true;
	  }

      vm.selectedSite = site;
      vm.loading = true;
      vm.templates = [];

      // Going to fetch remote templates
      var templatesUrl = remoteTemplatesPath + '&site=' + site.site_url;
      if (site.site_url.indexOf('localhost') !== -1) {
        templatesUrl = templatesPath;
      }

      getRemoteData(templatesUrl)
        .then(function(data) {
		  vm.loading = false;
			if (data) {
			  vm.templates = data;
			}
        })
        .catch(function(error) {
          vm.loading = false;
          alert('Sorry, Syncer could not load the templates on the selected site. Please try again later.');
        })
    },
    handleDownload: function(entry) {
      var vm = this;
      vm.loading = true;

      var templateUrl = getTemplateUrl(entry.template_id, this.selectedSite.site_url);
      if (this.selectedSite.site_url.indexOf('localhost') !== -1) {
        templateUrl = getTemplateUrl(entry.template_id);
      }

      // Step 1 - Download of the remote site
      getRemoteData(templateUrl)
        .then(function(data) {
          // Step 2 - Tell others
          vm.loading = false;
          vm.$emit('downloaded');
          alert('Success! Template downloaded.');
        })
        .catch(function(error) {
          vm.loading = false;
          alert('Sorry, downloading this template failed. Please try again.');
        })
    }
  }
})
</script>

<script type="text/x-template" id="toolkit_syncer_all_templates">
	<remote-templates @downloaded="refreshLocal" :toggleActive="toggleActive" @toggleActive="this.$emit('toggleActive')"/>
</script>

<script type="text/javascript">
Vue.component('all-templates', {
  template: '#toolkit_syncer_all_templates',
  props: ['toggleActive'],
  data: function() {
    return {
      localTemplates: [],
      loadingLocal: false,
    }
  },
  methods: {
    refreshLocal: function() {
      this.getLocalTemplates();
    },
    getLocalTemplates: function() {
      var vm = this;
      vm.loadingLocal = true;

      // Get local tempaltes
      getRemoteData(templatesPath)
        .then(function(data) {
          vm.localTemplates = data;
          vm.loadingLocal = false;
        })
        .catch(function(error) {
          alert('Sorry, we could not display or load your local templates. Please try again.');
          vm.loadingLocal = false;
        });
    }
  },
  created: function() {
    //setTimeout(this.getLocalTemplates, 1000);
  },
});
</script>

<div id="syncer-template">
  <div v-if="active" id="site-connection-manager">
	<remote-templates :disablesyncer="toggleActive"/>
  </div>

  <h3 v-else class="clearfix">
	Quickly download saved Elementor templates on your other sites via Direct Connection.
    <button :title="keyVerified ? 'Click to activate' : 'Please verify the key first'" :disabled="!keyVerified" class="button toolkit-btn toggle-button" @click="toggleActive">
        Enable Syncer
    </button>
  </h3>

</div>

<script type="text/javascript">
jQuery(document).ready(function() {
  var chat = new Vue({
    el: '#syncer-template',
    data: function() {
      var activated = localStorage.getItem('syncerActivated');
      return {
        keyVerified: keyVerified,
        active: activated === 'true' ? true : false,
      };
    },
    methods: {
      toggleActive: function() {
        var newState = !this.active;
        this.active = newState;
        localStorage.setItem('syncerActivated', newState);
      }
    },
  });
})
</script>
