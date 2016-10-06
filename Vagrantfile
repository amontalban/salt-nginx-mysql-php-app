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
    config.vm.define :minion10 do |minion10|
    configure_proxy(minion10)
    minion10.vm.box = vm_box
    minion10.vm.box_check_update = true
    minion10.vm.network :private_network, ip: '192.168.1.10'
    minion10.vm.hostname = 'minion10'
    minion10.vm.provision :shell, path: "minion10_bootstrap.sh"
  end

end
