guard 'phpunit', :tests_path => 'Test', :cli => '--colors' do
  # watch test files
  watch(%r{^.+Test\.php$})

  #watch Domain
  watch(%r{^Domain/(.+)\.php}) { |m| "Tests/#{m[1]}Test.php" }

end