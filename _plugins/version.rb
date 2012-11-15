module Jekyll
  module VersionFilter
    def versioned_url(input)
      "http://localhost:8888/css/global.css#{input}?#{Time.now.to_i}"
    end
  end
end

Liquid::Template.register_filter(Jekyll::VersionFilter)