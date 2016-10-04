Vagrant.configure('2') do |config|

  # Proxy Settings
  def configure_proxy(vm_def)
    if Vagrant.has_plugin?("vagrant-proxyconf")

      vm_def.proxy.http = ENV['http_proxy']
      vm_def.proxy.https = ENV['https_proxy']
      vm_def.apt_proxy.http = ENV['http_proxy']
      vm_def.apt_proxy.https = ENV['https_proxy']
      vm_def.proxy.no_proxy = ENV['no_proxy']
    end
  end

  vm_box = 'ubuntu/xenial64'


  # The Minion VM
    config.vm.define :minion do |minion|
    configure_proxy(minion)
    minion.vm.box = vm_box
    minion.vm.box_check_update = true
    minion.vm.network :private_network, ip: '192.168.1.2'
    minion.vm.hostname = 'minion'
    minion.vm.provision :shell, path: "minion_bootstrap.sh"
  end

end
